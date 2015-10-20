<?php

$pageURL = $_GET['pageurl'];

if(substr($pageURL, strlen($pageURL) -1 ) == "/") $pageURL .= "index.html";

$filename = getenv("DOCUMENT_ROOT").$pageURL;

include($filename);



?>

