<?php

$line = array("message"=>'+----+---------------+----------+-------------------+
| id | query         | protocol | host              |
+----+---------------+----------+-------------------+
| 12 | "select"      | "http"   | "127.0.0.1:49172" |
| 11 | "parse_error" | "http"   | "127.0.0.1:49170" |
+----+---------------+----------+-------------------+
2 rows in set (32.82 sec)'
);

//$line = str_replace(array("\r\n", "\n", "\r"), "\\n", $line);

//echo(json_encode($line, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_SLASHES ));
echo(json_encode($line));
//echo(json_encode(json_decode(stripslashes($line))));
echo("\n");
