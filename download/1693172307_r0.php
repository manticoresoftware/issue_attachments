<?php

function MyMicrotime ()
{
	$q = @gettimeofday();
	return (float)($q["usec"] / 1000000) + $q["sec"];
}


function RunQueries ( $no_cache )
{
//	$query = "select comment_ranking, avg(author_comment_count+story_comment_count) avg from hackernews_col50x group by comment_ranking order by avg desc limit 20 option ranker=none";
//	$query = "select comment_ranking from hackernews_col50x group by comment_ranking limit 20 option ranker=none";
//	$query = "select comment_ranking from hackernews_col50x group by story_author limit 20 option ranker=none";
//	$query = " select count(*) from test where any(multi0)<1000;";
//	$query = "select count(*) from hackernews_col100x where comment_ranking in (100,200)";
//	$query="select count(*) from hackernews_col100x group by story_author limit 20";
	$query = "select count(*) from hackernews_col100x where comment_ranking>0 force index(comment_ranking);";
	print ( $query."\n" );

	$sphinx = mysqli_connect ("127.0.0.1:8306");
	if ( !$sphinx )
		die ('sphinx connect error'.mysqli_connect_error());

	$cache_size = $no_cache ? 0 : 16777216;

	$res = mysqli_query ( $sphinx, "set global qcache_max_bytes=".$cache_size );
	if ( !$res )
		die ("Query failed ".mysqli_error($sphinx) );

	$total_time = 0;
	$my_total_time = 0;

	$NUM_QUERIES = 100;
	for ( $i = 0; $i < $NUM_QUERIES; $i++ )
	{
		$mytime = MyMicroTime ();
		$res = mysqli_query ( $sphinx, $query );
		$my_total_time += MyMicroTime ()-$mytime;
		if ( !$res )
			die ("Query failed ".mysqli_error($sphinx) );

		mysqli_free_result($res);

		$qtime = 0;
		$res = mysqli_query ( $sphinx, "SHOW META" );
		while ( $row = @mysqli_fetch_row($res) )
		{
			if ( $row[0]=="time" )
			{
				$total_time += $row[1];
				break;
			}
		}

		mysqli_free_result($res);
	}

	mysqli_close ( $sphinx );

	printf ( "time: (int)%.3f (ext)%.3f\n", $total_time, $my_total_time );
}


$num_runs = 2;
for ( $i = 0; $i < $num_runs; $i++ )
    RunQueries(true);


?>