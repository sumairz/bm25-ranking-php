<?php 

include 'functions.php';

$docID = trim(htmlspecialchars($_POST['docID']));

$query = applyLowerCase($_POST['keyword']);

$keyword = explode(' ' , $query);

$tokens = makeQueryTokens($query);

$tokens = removeStopWords($tokens);

$tokens = removeNumericTokens($tokens);


$content = file_get_contents("content/".$docID.".txt"); // reading giving document
$content = strtolower($content);

$i = 0;
foreach ($tokens as $word)
{
	if ($i > 0)
		$content = $new;
		
	$searchingFor = "/" . $word . "/i";
	$replacePattern = "<b style='font-size:25px;'>$0</b>";
	$new = preg_replace($searchingFor, $replacePattern, $content);
	$i = $i + 1;	
}

echo $new;

?>