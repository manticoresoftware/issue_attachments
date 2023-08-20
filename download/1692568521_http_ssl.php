<?php

//  ./test.php --plugin=http_ssl.php --data=/home/snikolaev/twitter/queries_100K_es.txt -b=100 -c=2 --limit=2 --port=7381 --select_limit=10 --ssl=1 --reconnect=1

class plugin {

	private function create_con() {
		if ( isset ( $this->ci ) )
			curl_close ( $this->ci );
		
		$this->ci = curl_init();
		curl_setopt($this->ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ci, CURLOPT_URL, $this->url);

		//curl_setopt($this->ci, CURLOPT_VERBOSE, 1);

		if ( $this->ssl ) {
			curl_setopt($this->ci, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ci, CURLOPT_SSL_VERIFYHOST, 0);
		}
		
		if ( $this->reconnect ) {
			curl_setopt($this->ci, CURLOPT_FORBID_REUSE, 1);
		}
	}


	public function init() {
		$options = getopt('', array('port::', 'index::', 'select_limit::', 'ssl::', 'reconnect::'));

		$port = isset($options['port']) ? $options['port'] : 8380;
        $index = isset($options['index']) ? $options['index'] : 'idx';		
		$limit = isset($options['select_limit']) ? $options['select_limit'] : 20;
		$this->ssl = isset($options['ssl']) ? $options['ssl'] : 0;
		$this->reconnect = isset($options['reconnect']) ? $options['reconnect'] : 0;
		
		$this->url = ( $this->ssl ? "https" : "http" ) . "://localhost:" . $port . "/sql?mode=raw&query=SELECT%20*%20FROM%20" . $index . "%20LIMIT%20" . $limit;

		$this->create_con();
	}

	public function query($queries) {
		$out = array();
		foreach ($queries as $id=>$query) {
			
			if ( $this->reconnect )
				$this->create_con();
			
			$t = microtime(true);
			$res = curl_exec($this->ci);
			$t_end = microtime(true);
			
			// !COMMIT
			//print($res);
			
			$res = json_decode($res);
			$total = 1;
			if ( isset($res->hits) && isset($res->hits->total) )
				$total = $res->hits->total;
			
			$out[$id] = array('latency' => $t_end - $t, 'hits' => $total);
		}
		return $out;
	}

	public static function report($queriesInfo) {
		$totalMatches = 0;
		foreach($queriesInfo as $id => $info) {
			$totalMatches += $info['hits'];
		}
		return array(
		'Total matches' => $totalMatches,
		'Count' => count($queriesInfo));
	}
}
