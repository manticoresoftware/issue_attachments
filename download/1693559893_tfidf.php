<?php
$docs = array(
        1 => array('text' =>"My dog doesn't like all my pets, but me and my wife do love them all. A dog cannot be a cat lover"),
	2 => array('text' =>"Cats and dogs do not like each other, but my cat does like dogs"),
	3 => array('text' =>"Walking a dog is a good start of the day"),
	4 => array('text' =>"Not all cats like walking, but some cats do"),
        5 => array('text' => "All dogs like walking, but mine doesn't like. It's like so weird"),
);
$lemmas = array(
	"dogs" => "dog",
	"cats" => "cat"
);
$dfs = array(); $docs_split = array(); $length_sum = 0;
foreach ($docs as $id=>$doc) {
	$terms = preg_split('/[ ,.]+/', strtolower($doc['text']));
	$tf = array();
	foreach ($terms as $k=>$term) {
		if (isset($lemmas[$term])) $terms[$k] = $lemmas[$term];
		@$tf[$terms[$k]]++;
	}
	arsort($tf);
	@$docs[$id]['tf'] = $tf;

	$terms_unique = array_unique($terms);
	foreach ($terms_unique as $term) @$dfs[$term]++;

	$docs[$id]['length'] = count($terms);
	$length_sum += count($terms);
}
arsort($dfs);
echo "Stats\n";
foreach($dfs as $term=>$v) {
	echo "$term: df - $v, tf: ";
	foreach ($docs as $doc) if (isset($doc['tf'][$term])) echo $doc['tf'][$term]." ";
	echo "\n";
}

$avg_length = $length_sum / count($docs);

$query_terms = preg_split('/[ ,.]+/', strtolower($argv[1]));

echo "Formula: ".'$weight = '.$argv[2]."\n";

foreach ($docs as $id=>$doc) {
	$tf = 0; $df = 0; $idf = 0; $weight = 0;
	$docs[$id]['weights'] = array('tf' => 0, 'df' => 0, 'idf' => 0, 'total' => 0, 'per-term' => array());
	foreach ($query_terms as $term_id=>$query_term) {
		$tf = @$doc['tf'][$query_term];
		$df = @$dfs[$query_term];
		$idf = $df>0?count($docs)/$df:0;
#		$weight = log($tf)*log(1+$idf);
#		$weight = $tf;
		eval('$weight = '.$argv[2].';');
	        $docs[$id]['weights']['tf'] += $tf;
	        $docs[$id]['weights']['df'] += $df;
	        $docs[$id]['weights']['idf'] += $idf;
	        $docs[$id]['weights']['total'] += $weight;
		$docs[$id]['weights']['per-term'][$query_term] = array('tf'=>$tf?$tf:0,'df'=>$df?$df:0,'idf'=>$idf,'weight'=>$weight);
#echo "-----------$query_term in {$doc['text']}\n";
#print_r($doc);
#echo "tf=$tf;df=$df;idf=$idf;weight=$weight\n";

#		if (isset($doc['tf'][$query_term])) $docs[$id]['text'] = preg_replace("/(\W|^)({$query_term}s*)(\W|$)/i", "$1\e[0;31;42m$2\e[0m(".round($idf, 2).")$3", $docs[$id]['text']);
               if (isset($doc['tf'][$query_term])) $docs[$id]['text'] = preg_replace("/(\W|^)({$query_term}s*)(\W|$)/i", "$1\e[0;31;42m$2\e[0m$3", $docs[$id]['text']);
	}
}
uasort($docs, create_function('$a,$b', '
    if ($a["weights"]["total"] == $b["weights"]["total"]) return 0;
    return ($a["weights"]["total"] < $b["weights"]["total"]) ? 1 : -1;
'));
foreach ($docs as $id=>$doc) {
	foreach ($docs[$id]['weights'] as $k=>$v) if ($k != 'per-term') $docs[$id]['weights'][$k] = round($v, 2);
	echo "$id. {$doc['text']}\n";
#        echo "$id. {$doc['text']} | tf = ".$doc['weights']['tf'].", idf = ".$doc['weights']['idf']."\n";
        echo "Weights: ".json_encode($docs[$id]['weights'], JSON_PRETTY_PRINT)."\n\n";

}
