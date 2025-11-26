<?php

$fileHandle = fopen("feed_2performant.csv", "w");

$dataCollection = array(
	'Cleste.ro',
	'');

fputcsv($fileHandle, $dataCollection);


fclose($fileHandle);


?>