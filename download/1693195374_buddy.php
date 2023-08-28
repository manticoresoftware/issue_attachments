<?php declare(strict_types=1);

include 'utils.php';


$longopts  = [
	'pid:',
	'pid_path:',
];
/** @var string $pid */
/** @var string $pidPath */
// @phpstan-ignore-next-line
['pid' => $pid, 'pid_path' => $pidPath] = getopt('', $longopts);

const ACTION_MAP = [
    'NO_INDEX' => [
        'INSERT_QUERY' => 'CREATE_INDEX',
    ],
    'UNKNOWN_COMMAND' => [
        'SHOW_QUERIES_QUERY' => 'SELECT_SYSTEM_SESSIONS',
    ],
];

const ENDPOINT_MAP = [
	'/sql?mode=raw' => ['CREATE_INDEX'], 
	'/sql' => ['SELECT_SYSTEM_SESSIONS'],
];

// bundles are sorted by type priority from the highest to the lowest
const TYPE_BUNDLES = [
	['json'],
	['multi64', 'multi'],
	['float', 'bigint', 'int'],
	['text', 'string'],
];


$socket = setup();

// checking if request can be handled by Buddy
/**
 * @param string $type
 * @param string $query
 * @return false|string
 */
function get_handle_action(string $errorMsg, string $query) {
    $errorType = detect_error_type($errorMsg);
    //file_put_contents('/tmp/test.txt', "3-$errorType\n", FILE_APPEND);
    if (isset(ACTION_MAP[$errorType])) {
        $queryType = detect_query_type($query);
        //file_put_contents('/tmp/test.txt', "4-$queryType\n", FILE_APPEND);
        return ACTION_MAP[$errorType][$queryType] ?? false;
    }
    return false;
}


/**
 * @param string $action
 * @return false|string
 */
function get_resp_uri(string $action) {
	foreach(ENDPOINT_MAP as $k => $v) {
		if (in_array($action, $v)) {
			return $k;
		}
    }
    return false;
}


//file_put_contents('/tmp/test.txt', "\n");
// listening socket in loop
while (true) {
	$conn = socket_accept($socket);
	if ($conn === false) {
		if (!check_client($pid, $pidPath)) {
			send_die_msg('Client is dead');
		}
		usleep(1000);
		//echo "iter empty\n"; // !COMMIT
	} else {
		// receiving request from the Manticore server
		$req = read_socket_msg($conn);
		if ($req === false) {
			die('failed to read socket msg');
		}
		
		['type' => $errorMsg, 'message' => $query, 'endpoint' => $reqURI] = $req;
		$resp = ['type' => $errorMsg, 'message' => $query, 'endpoint' => $reqURI];
        
		//file_put_contents('/tmp/test.txt', "1-$errorMsg\n", FILE_APPEND);
		//file_put_contents('/tmp/test.txt', "2-$query\n", FILE_APPEND);
		$handleAction = get_handle_action($errorMsg, $query);
		//file_put_contents('/tmp/test.txt', "5-$handleAction\n", FILE_APPEND);
		if ($handleAction) {
			$respContent = build_resp_content($handleAction, $query);
			if ($respContent === false) {
				write_socket_msg($conn, $resp);
				continue;
    		}
    		
    		['type' => $respType, 'message' => $respMsg] = $respContent;
    		// sending response to the Manticore server
    		$respURI = get_resp_uri($handleAction);
    		if ($respURI === false) {
				write_socket_msg($conn, $resp);
				continue;
    		}
    		// extra formatting and encoding of response for /sql endpoint
    		if (str_starts_with($respURI, '/sql')) {
    			$respMsg = encode_response($respMsg);
    		}
    		$resp = ['type' => $respType, 'message' => $respMsg, 'endpoint' => $respURI];
    		write_socket_msg($conn, $resp);
    		//echo "iter send\n"; // !COMMIT
		} else
		{
			write_socket_msg($conn, $resp);
		}
		#file_put_contents('/tmp/test.txt', "6\n", FILE_APPEND);
	}
}
