<?
require_once('mysqliresultset.php');
require_once('mysqlistatementex.php');
require_once 'common/classes/utils.php';

class Persistence {
    
	//Hugly MySQL LIMIT: If you specify OFFSET it's mandatory LIMIT.
	//The number is taken from their guide!
	public $MAX_mysqli_LIMIT = "18446744073709551615";
	public $db = null;
	public $sdb = null;	// Sphinx DB

	public static $WEB_USER = 0;
	public static $MOBILE_USER = 1;
	public static $REST_USER = 2;

	// Cache an associative array of languages indexed by code
	private $languagesIndex = null;

	// Cache an associative array of countries indexed by code
	private $countriesIndex = null;
	private $searchableCountriesIndex = null;	// just the countries with data online
	
	// Cache an associative array of source indexed by code
	private $infoSourcesIndex = null;
	
	private $medTypesIndex = null;
	private $medDomainsIndex = null;
	private $medCategoriesIndex = null;

	public function __destruct() {
		if($this->db != null) {
			$this->db->close();
		}
		if($this->sdb != null) {
			$this->sdb->close();
		}
	}

	public function __construct($userType) {
	
		include "common/config/configuration.php";

		$this->db = new mysqli("p:".$database['server'], $database['username'], $database['password']);
		if ($this->db->connect_errno) {
		    Logger::error("Connect failed: " . $mysqli->connect_error . PHP_EOL);
		    $this->db = null;
		    exit();
		}
		$this->db->select_db($database['name']);
		$this->db->query("SET CHARACTER SET utf8");
		
		// Sphinx database initialization
		$this->sdb = new mysqli("127.0.0.1", "", "", "", 9306);
		if ($this->sdb->connect_errno) {
		    Logger::error("Connect to Sphinx failed: " . $this->sdb->connect_error . PHP_EOL);
			$this->sdb = null;
			exit();
		}
		// else echo 'Success... ' . mysqli_get_host_info($this->sdb) . "\n";	
	}

	function simpleQuery($query) {
		$ret = $this->db->query($query);
		if(!$ret)die(mysqli_error());
		return  $ret;
	}

	function realEscape($str) {
		return mysqli_real_escape_string($this->db, $str);
	}
	
	function searchSphinx   (
                            $searchString,
            				$country,
            				$language,
            				$source,
            				$limit,
            				$from,
            				&$foundRows,
            				&$filterModifier,
            				$always_return_results	// 1=use filter modifiers 0=do not use
                            )
    {
        $res = null;
        $foundRows = -1;
        
        $from = Utils::parseInt($from, 0);
		if($from < 0) $from = 0;
		
		$limit = Utils::parseInt($limit, 0);
		if($limit <= 0) $limit = 100;
		
        // clean up search string
        $searchString = $this->sdb->real_escape_string(str_replace(UserManager::$chars_to_clean_query_string, ' ', $searchString));
		
		$suggestString = null;
		$quorumString = null;
		
	    // $orig_search_string = $searchString; 	            // needed for the snippet
		
		$bits = preg_split('/\s+/',trim($searchString));    // how many words in this search phrase
		$all_bits = count($bits);
		
		// only count words that are over 2 chars long
		$n_bits = 0;
		foreach ($bits as $bit) {
		    if (strlen($bit) > 2)
		        $n_bits++;
		}
		if ($n_bits == 0) $n_bits = 1;
		
		$quorum = ceil($n_bits*0.50);
		$quorumString = '"' . $searchString . '"/' . $quorum;
		$proximityString = '"' . $searchString . '"~' . $all_bits;
		
		//
        // Pass 1 - run query with all parameters unmodified, search only in the name, commercial_name, active_ingredients fields
        //
        // Logger::debug("searchSphinx Pass 1, search in name and commercial_name fields ... " . PHP_EOL);
        $fieldsString = '@(name,commercial_name,active_ingredient) "' . $searchString . '"~4';
        $res = Persistence::runSphinxQuery ($fieldsString, $searchString, $country, $language, $source, $limit, $from, $foundRows);
       
        //
        // Pass 2 - run query with all parameters unmodified in all fields
        //
        if ($foundRows < 1) {                                               // nothing found in previous pass
                
    		// Logger::debug("searchSphinx Pass 2, search in all fields ... " . PHP_EOL);
    		$res = Persistence::runSphinxQuery ($searchString, $searchString, $country, $language, $source, $limit, $from, $foundRows);
        }
        
        //
        // Pass 3 - run query with all parameters unmodified use SUGGEST or QUORUM to widen search
        //
        if ($foundRows < 1) {                                           // nothing found in previous pass
            
    		if ($n_bits == 1) {                                    // only 1 search term,use SUGGEST for alternative term to use
    		    // Logger::debug("searchSphinx Pass 3, using SUGGEST ... " . PHP_EOL);
    		    
    		    // find the closest term for search
    		    // CALL qsuggest('humalogg', 'idx1p0');
    		    $suggest_query = "CALL SUGGEST ('" . $searchString . "', 'idx1p0')";
    		    // Logger::debug("searchSphinx Pass 3, SUGGEST call: " . $suggest_query . PHP_EOL);
        		
        		if ($suggest_result  = $this->sdb->query($suggest_query)) {
        		    
        		    $best_row = null;
        		    $best_docs = 0;
        		    while ($row = $suggest_result->fetch_assoc()) {
                        if ($row['docs'] > $best_docs) {
                            $best_docs = $row['docs'];
                            $best_row = $row;
                        }
                    }
                    
        		    if ($best_row != null) {
            		    // print_r($row);
            		    $suggestString = $best_row['suggest'];          // replace search string with closest value
        		        $res = Persistence::runSphinxQuery ($suggestString, $suggestString, $country, $language, $source, $limit, $from, $foundRows);
            		}
    		    }
        		else {
        		    Logger::warning("searchSphinx Pass 3, suggest call failed: " . $this->sdb->error . PHP_EOL);
        		}
    		}
    		else {
    		    // Logger::debug("searchSphinx Pass 3, using PROXIMITY ... n_bits: " . $all_bits . PHP_EOL);
    		    
        		// use proximity modifier for search string
        		$res = Persistence::runSphinxQuery ($proximityString, $searchString, $country, $language, $source, $limit, $from, $foundRows);
    		}
        }
        
        // repeat the searches as above but with the language and country filters disabled
        // (not used for API calls)  (this is the widest range of search with highest probability of returning results)
        if ($always_return_results) {           // search bar has this = true, API calls have this = false
            
            if ($foundRows < 1) {               // nothing found in previous passes
                
    		    // Logger::debug("searchSphinx Pass 4, remove language and country filters, search in all fields ... " . PHP_EOL);
    		    $res = Persistence::runSphinxQuery ($searchString, $searchString, null, null, $source, $limit, $from, $foundRows);
            }
            
            //
            // Pass 5 - run query and remove language and country filters
            //
            if ($foundRows < 1) {
                
                if ($n_bits == 1) {                                                         // only 1 search term,use SUGGEST for alternative term to use
                    
        		    if ($best_row != null) {
        		        // Logger::debug( "searchSphinx Pass 5, remove language and country filters, use SUGGEST ... " . PHP_EOL);
        		        $res = Persistence::runSphinxQuery ($suggestString, $suggestString, null, null, $source, $limit, $from, $foundRows);
            		}
                }
                else {
                    // Logger::debug( "searchSphinx Pass 5, remove language and country filters, use QUORUM ... " . PHP_EOL);
                    $res = Persistence::runSphinxQuery ($quorumString, $searchString, null, null, $source, $limit, $from, $foundRows);
                }
            }
        }
        
        return $res;
    }
	
	// Returns an iterator
	// filterModifier is used to apply modifications automatically to the search filter in order to return results when the query with the current
	// filters gives an empty result set
	function runSphinxQuery	(
            				$searchString,
            				$orig_search_string,
            				$country,
            				$language,
            				$source,
            				$limit,
            				$from,
            				&$foundRows
            				) 
    {
		// this query uses the SNIPPET function option to load the file directly but at the moment it seems to be limited to 1 file
		$select_clause = "SELECT *, SNIPPET(leaflet_pil_preview_name, '". $orig_search_string . "', 'load_files=1, html_strip_mode=index, around=10') AS snippet FROM myHB_index1 WHERE MATCH ('" . $searchString . "')";
		
		$query = $select_clause;
		
		// Add country filter
		if($country != "" ) {
			
			// allow for special cases (like Europe) where marketing authorizations cover more than 1 country
			if ($this->isEuropean($country)) {
				$country_clause = " AND (MAC_code IN (36, " . $country . "))";
			}
			else $country_clause = " AND (MAC_code = " . $country . ")";
			
			$query = $query . $country_clause;
		}

		// Add language filter
		if($language != "" ) {
			
			if ($language == 8) {                                           // english root language (en_GB)
			    $language_clause = " AND (language_code IN (8, 27, 43))";   // must include alllanguages with english as root (en_GB, en_US, en_ZA etc...)
			}
			else if ($language == 2) {                                      // spanish root language (es_ES)
			    $language_clause = " AND (language_code IN (2, 41, 46))";   // add es_AR, es_VE
			}
			else if ($language == 17) {                                         // portuguese root language (pt_PT)
			    $language_clause = " AND (language_code IN (17, 26))";          // add pt_BR
			}
			else $language_clause = " AND (language_code = " . $language . ")";
			
			$query = $query . $language_clause;
		}
		
		// Add source filter
		if($source != "" ) {
			$source_clause = " AND (info_source_code = " . $source . ")";
			$query = $query . $source_clause;
		}
	
	    // Add from and limit
	    $from = Utils::parseInt($from, 0);
		if($from < 0) $from = 0;
		
		$limit = Utils::parseInt($limit, 0);
		if($limit <= 0) $limit = 100;		
		
		// $group_by_clause = " GROUP BY ";
		
		$limit_clause = " LIMIT " . $from . "," . $limit;
		
		$options_clause = " OPTION max_matches=100, ranker=sph04";
		// $options_clause = " OPTION max_matches=100";
		// $options_clause = " OPTION max_matches=100, ranker=sph04, field_weights=(name=1000000)";
		
		// $query = $query . $group_by_clause . $limit_clause . $options_clause;
		$query = $query . $limit_clause . $options_clause;
			
		// Logger::debug("sphinxSearch query: " . $query . PHP_EOL);
	
	    // run the query
		if ($res = $this->sdb->query($query)) {
    			
    		// find the totals number of rows found regardless of limit
    		$meta_query  = $this->sdb->query("SHOW META;");
    		while($fm = mysqli_fetch_array($meta_query)) {
        		if ($fm['Variable_name'] == 'total_found') {
                    $foundRows = $fm['Value'];
        		}
			}
			$foundRows = ($foundRows > $limit ? $limit : $foundRows);
    		// Logger::warning("Sphinx total found rows: " .$foundRows . "\n");
			
			if ($foundRows > 0) {			
				return $res;
    		}
		}
		else {
		    Logger::warning("Sphinx call failed: " . $query . PHP_EOL);
		}
		
		/* free result set */
		if ($res)
		    $res->close();
		    
	    return null;
	}
}

?>
