<?php
require 'sphinxapi.php';

$sphinx_obj = new SphinxClient();
$sphinx_obj->SetServer( '127.0.0.1', 9312 );

$select="(IN(article_custom_fields.columnname, 'FOCUS'))";
$sphinx_obj->setSelect("*, ".$select." AS cond1");
$sphinx_obj->SetFilter('cond1',array(1));
$query_result = $sphinx_obj->Query( '', "website_archive_test" );


echo "<pre>";
print_r($sphinx_obj);
print_r($query_result);

?>

