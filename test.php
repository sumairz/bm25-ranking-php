<?php
/*
error_reporting(0);
include "functions.php";

$dir_files = scandir("content/");
unset($dir_files[0]);
unset($dir_files[1]);

$sum = 0;
foreach ($dir_files as $val)
{
	$file = file_get_contents("content/".$val);
	$tokens = explode(" ", $file);
	$tokens_content = removeDuplicateElements($tokens);
	$tokens_content = stripPunctuations($tokens_content);
	$tokens_content = removeStopWords($tokens_content);
	$tokens_content = removeNumericTokens($tokens_content);
	$sum = $sum + count($tokens_content);
}

echo $sum
*/
//echo 2662137 / 21578; // without normalization

echo 872205 / 21578; // with normalization
?>