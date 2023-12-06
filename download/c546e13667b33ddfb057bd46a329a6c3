<?php

$fh = fopen('author_node_1_full.log', 'r') or die($php_errormsg);
$i = 0;
while (!feof($fh)) {
    $line = fgets($fh);
	$out;
    if (preg_match("/.* - DEBUG - ({.*)/", $line, $out)) {
		print_r($out[1]);
		print_r("\n");
		//print_r("$i\n");
		$i++;
	}
}
fclose($fh);
