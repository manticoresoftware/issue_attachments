#!/usr/bin/php
<?php
if (count($argv) < 4) die("Usage: ".__FILE__." <batch size> <concurrency> <docs>\n");

// This function waits for an idle mysql connection for the $query, runs it and exits
function process($query) {
file_put_contents('/tmp/sql', $query.";\n", FILE_APPEND);
    global $all_links;
    global $requests;
    foreach ($all_links as $k=>$link) {
        if (@$requests[$k]) continue;
        mysqli_query($link, $query, MYSQLI_ASYNC);
        @$requests[$k] = microtime(true);
        return true;
    }
    do {
        $links = $errors = $reject = array();
        foreach ($all_links as $link) {
            $links[] = $errors[] = $reject[] = $link;
        }
        $count = @mysqli_poll($links, $errors, $reject, 0, 1000);
        if ($count > 0) {
            foreach ($links as $j=>$link) {
                $res = @mysqli_reap_async_query($links[$j]);
                foreach ($all_links as $i=>$link_orig) if ($all_links[$i] === $links[$j]) break;
                if ($link->error) {
                    echo "ERROR: {$link->error}\n";
                    if (!mysqli_ping($link)) {
                        echo "ERROR: mysql connection is down, removing it from the pool\n";
                        unset($all_links[$i]); // remove the original link from the pool
                        unset($requests[$i]); // and from the $requests too
                    }
                    return false;
                }
                if ($res === false and !$link->error) continue;
                if (is_object($res)) {
                    mysqli_free_result($res);
                }
                $requests[$i] = microtime(true);
                mysqli_close($link); $all_links[$i] = @mysqli_connect('127.0.0.1', '', '', '', 9315); $link = $all_links[$i];
		mysqli_query($link, $query, MYSQLI_ASYNC); // making next query
                return true;
            }
        };
    } while (true);
    return true;
}

$t = microtime(true);
$all_links = [];
$requests = [];
$c = 0;
for ($i=0;$i<$argv[2];$i++) {
  $m = @mysqli_connect('127.0.0.1', '', '', '', 9315);
      if (mysqli_connect_error()) die("Cannot connect to Manticore\n");
      $all_links[] = $m;
  }

// init
$batch = [];
$query_start = "REPLACE INTO index_10 (subareaabbrev, haspic, postinguuid, firstdate, keywords, posted, latitude, salecondition, id, neighborhood, recordcreated, salemanufacturer, catabbrev, subareaid, longitude, geocodedtxt, accountid, posteddate, ask, imageids, categoryid, invoice, categorydesc, title, type, attrtext, attributes, salemodel, imagecaptions, lastdate, userid, postingcity, ip, recordmodified, postingregion, attr, geospam, body, profileid, areaabbrev, bodyspam, saledate, autovin, areaid, sqft, dedupekey, simwords, latlon, paid, makemodel, geoarea, postingtitle, posterip, attrtextextra, geographicarea, bedrooms) VALUES ";

srand(0);
$error = false;
while (count($all_links) and $c < $argv[3]) {
$id = rand(1,floor($argv[3]*2));
  $batch[] = "('', '0', '8IOCQmwh7BGHKXhdm1xeQg', '1632985200', ' 7387355611', '17', '0.634819882215789', '', '".$id."', '', '1632951600', '', 'gms sss sso', '0', '-1.6757307575027', '15825 N 21St E Ave  Skiatook OK US 74070 74070', '', '1632951840', '', '', '73', '0', 'garage & moving sales', 'Huge! Garage Sale Paradise! Multi Family 3 Day Sale', '10', '', (), '', '', '1633158000', '51007881', 'Skiatook', '1800251652 ip107v77v169v4', '1632951600', 'OK', '{\"sale_date_2\":1633046400,\"language\":\"5\",\"cl_flag_count\":0,\"cl_geocoder_accuracy\":\"22\",\"sale_date_1\":1632960000,\"sale_date_3\":1633132800,\"cl_keywords_deleted\":0}', '', 'You Don\'t want to miss this sale! It has all kinds of nice things, a very large variety of things at great prices! If you like to buy to resell you want to come to this sale! If you like jewelry, or DVD\'s, or knick knacks, or vintage collectibles, or miscellaneous stuff, or new toys, or new and used clothing, and all kinds of whatever this is the sale to be at. Organized, and clean! This is better than an Estate sale because we have garage sale prices!!! Located in Skiatook @ 15825 N. 21st East Avenue we are just East of Skiatook off Highway 20. we Hope to see you at the sale!! We are just North of Tulsa most places in Tulsa are under 30 minutes from here.', '', 'tul', '', '2021-09-30 2021-10-01 2021-10-02', '', '70', '', '33892f5f5f94b3b15e592619fed8b37a', '', '10286339782757', '', ' ', 'Skiatook  ', 'Huge! Garage Sale Paradise! Multi Family 3 Day Sale', '1800251652 ip107v77v169v4', '8 am 2021-09-30 2021-10-01 15825 N 21St E Ave 74070 Skiatook 2021-10-02 Skiatook', 'Skiatook  ', '')";
  $c++;
  if (count($batch) == $argv[1]) {
    if (!process($query_start.implode(',', $batch))) {
      $error = true;
      break;
    }
    $batch = [];
  }
}
if (!$error and count($all_links) and $batch) process($query_start.implode(',', $batch));

// wait until all the workers finish
do {
  $links = $errors = $reject = array();
  foreach ($all_links as $link)  $links[] = $errors[] = $reject[] = $link;
  $count = @mysqli_poll($links, $errors, $reject, 0, 100);
} while (count($all_links) != count($links) + count($errors) + count($reject));

echo "finished inserting\n";
echo "Total time: ".(microtime(true) - $t)."\n";
echo round($argv[3] / (microtime(true) - $t))." docs per sec\n";
