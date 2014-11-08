<?php 
$time_start = microtime(true);
error_reporting(0);
include 'functions.php';

$intersect = Array();

$query = trim(htmlspecialchars($_POST['keyword']));

$query = applyLowerCase($query);

$tokens = makeQueryTokens($query);

$tokens = removeStopWords($tokens);

$tokens = removeNumericTokens($tokens);

$k = 1;
foreach ($tokens as $val)
{
	$keyName = "q".$k;
	
	$result = doSearch($val);
	
	if($result)
	{
		$data[$keyName] = $result;
		$k = $k + 1;
	}
	else 
		echo "<p style='color:red;'>No result found for ".$val."</p>";
}

$k = 1;
foreach ($data as $val)
{
	$keyName = "q".$k;
	
	$set = explode(",", $val);
	$df = count($set);
	
	foreach ($set as $v)
	{
		$s = explode(":", $v);		
		
		$ready[$keyName][$s[0]]['tf'] = intval($s[1]);
		$ready[$keyName][$s[0]]['df'] = $df;		 
	}
	
	$k = $k + 1;
}

$finalArray = Array();
//Merging each query arrays into one 1 array
foreach ($ready as $m)
{
	$finalArray = mergeArrays($finalArray,$m);
}

// Getting BM25 scores of each documents
foreach ($finalArray as $key=>$fdata) {
	$finalArray[$key]['bm25_score'] = BM25($finalArray[$key]['df'], $finalArray[$key]['tf'], $finalArray[$key]);
	$sortedArray[$key] = $finalArray[$key]['bm25_score'];
}

arsort($sortedArray);
//var_dump($sortedArray);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "<br /><br />Search time is : <b> ".round($time,2)." microseconds</b>";

foreach ($sortedArray as $key=>$val)
{
	echo "<div id='box'><p class='b1'><b>Doc ID: </b>".$key."</p><p class='b2'><b>BM25 score: </b>".$finalArray[$key]['bm25_score']."</p><p class='b3'><b>Term Freq.: </b>".$finalArray[$key]['tf']."</p><p class='b4'><b>Doc. Freq.: </b>".$finalArray[$key]['df']."</p></div>";
}

?>