<?php

$fh = fopen('doc_only.log', 'r') or die($php_errormsg);
$ch = curl_init( "http://localhost:8312/bulk" );

$i = 0;
while (!feof($fh)) {
    $line = fgets($fh);
    
    //$payload = json_encode( $line );
    //curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $line );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-ndjson'));
    # Return response instead of printing.
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    
    $result = curl_exec($ch);
    
    $i++;
    if ( ( $i % 100 )==0 ) {
        print_r($i);
        print_r("\n");
    }
#    var_dump ( $result );
#    var_dump ( json_decode ( $result ) );
    if ( ((array)json_decode ( $result ))["errors"]==true ) {
        print_r($i);
        print_r($result);
        print_r("\n");
    }
}
curl_close($ch);
fclose($fh);
