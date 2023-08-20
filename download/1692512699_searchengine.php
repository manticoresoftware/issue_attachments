<?
require_once('environment.php');

abstract class AbstractSearchEngine {

	public static $MAX_RESULTS = 1024;	

	protected $foundRows = -1;
	protected $filterModifier = 0; // 0=none, 1=country filter removed, 2=language filter removed, 3=country and language filters removed
	
	protected $limit = -1;
	protected $from = -1;
	
	protected $resultsIterator = array();
	
	function __construct($limit = -1, $from = -1) {
		
		$this->persistence = Environment::getPersistence();
		
		$this->userauth = Environment::getUserManager();
		
		$limit = Utils::parseInt($limit, -1);
		$from = Utils::parseInt($from, -1);
		if($limit > AbstractSearchEngine::$MAX_RESULTS) {
			$limit = AbstractSearchEngine::$MAX_RESULTS;
		}
		$this->limit = $limit;
		$this->from = $from;
	}
	
	function getFoundRows() {
		return ($this->foundRows >= 0?$this->foundRows:0);
	}
	
	function getFilterModifier() {
		return ($this->filterModifier);
	}
	
	
	function getResultsIterator() {
		return $this->resultsIterator;
	}
	
	function getFrom() {
		return $this->from;
	}
	
	function getLimit() {
		return $this->limit;
	}
	
	public abstract function getIsFallback();
	
	public abstract function getDescription($separator);
}

class SphinxSearchEngine extends AbstractSearchEngine {
	
	private	$search_string = null;
	private	$search_country =  null;
	private	$search_language =  null;
	private	$search_source =  null;
	
	function __construct	(
            				$search_string,
            				$search_country,
            				$search_language,
            				$search_source = null,
            				$limit = -1,
            				$from = -1,
            				$always_return_results = 1
            				) 
    {
			
		// Logger::debug("SphinxSearchEngine construct: " . PHP_EOL);
		
		parent::__construct($limit, $from);
		
		// there is an issue when TOKENS are used in the query string, to fix this just convert them to lowercase
		$search_string = str_replace('MAYBE','maybe',$search_string);
		$search_string = str_replace('NEAR','near',$search_string);
		$search_string = str_replace('SENTENCE','sentence',$search_string);
		$search_string = str_replace('PARAGRAPH','paragraph',$search_string);
		$search_string = str_replace('ZONE','zone',$search_string);
		$search_string = str_replace('ZONESPAN','zonespan',$search_string);
		
		// on sphinx I do not need to transform the string
		$this->search_string = $search_string;
		
		
		// these are numerals
		$this->search_country = $search_country;
		$this->search_language = $search_language;
		$this->search_source = $search_source;
		
		if($this->search_string and $this->search_string != "") {	
			 
			// Logger::debug($this->search_string);
			
			$this->resultsIterator = $this->persistence->searchSphinx(
                            										$this->search_string,
                            										$this->search_country,
                            										$this->search_language,
                            										$this->search_source,
                            										$this->limit,
                            										$this->from,
                            										$this->foundRows,
                            										$this->filterModifier,
                            										$always_return_results
                            										);
		}
	}
	
	public function __destruct() {
		
		// Logger::debug("SphinxSearchEngine destruct: free result set: " . get_class($this->resultsIterator));
		if ($this->resultsIterator && is_a($this->resultsIterator, 'mysqli_result'))
		    $this->resultsIterator->free();
	}
	
	public function getIsFallback() {
		return "false";
	}
	
	public function getDescription($separator = " ") {
		return Utils::appendStringArrayWithSeparator(array($this->search_string),$separator);
	}
	
	public function getParamsForURL() {
	
		$search_url = "q=" . $this->search_string;
		
		if ($this->search_country) $search_url = $search_url . "&country=" . $this->search_country;
		if ($this->search_language) $search_url = $search_url . "&language=" . $this->search_language;
								
		return $search_url;
	}
}

?>
