<?php

function removeDuplicateElements($content)
{
	return array_unique($content);
}


// This function convert uppercase characters to lowercase
function applyLowerCase($content)
{
	return strtolower($content);
}


// This function is removing whitespaces in terms
function removeWhiteSpaces($content)
{
	return array_map('trim', $content);
}


function stripPunctuations($content)
{
	$whatToStrip = array("?","!",",",";",'"','"...');
	
	$tokensCount = count($content);	// counting tokens
		
	// Performing actions of each token
	for($k=0;$k<=$tokensCount;$k++)
	{
		$content[$k] = str_replace($whatToStrip, "", $content[$k]);
	}//END of tokenCount loop
	
	return $content;	
}


// This function check if the given word is stropword or not
function removeStopWords($tokens)
{
	// 177 stop words
	$stopWords = Array("will", "i", "a", "about", "above", "after", "again", "against", "all", "am", "an", "and", "any", "are", "aren't", "as", "at", "be", "because", "been", "before", "being", "below", "between", "both", "but", "by", "can't", "cannot", "could", "couldn't", "did", "didn't", "do", "does", "doesn't", "doing", "don't", "down", "during", "each", "few", "for", "from", "further", "had", "hadn't", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "he's", "her", "here", "here's", "hers", "herself", "him", "himself", "his", "how", "how's", "i", "i'd", "i'll", "i'm", "i've", "if", "in", "into", "is", "isn't", "it", "it's", "its", "itself", "let's", "me", "more", "most", "mustn't", "my", "myself", "no", "nor", "not", "of", "off", "on", "once", "only", "or", "other", "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "so", "some", "such", "than", "that", "that's", "the", "their", "theirs", "them", "themselves", "then", "there", "there's", "these", "they", "they'd", "they'll", "they're", "they've", "this", "those", "through", "to", "too", "under", "until", "up", "very", "was", "wasn't", "we", "we'd", "we'll", "we're", "we've", "were", "weren't", "what", "what's", "when", "when's", "where", "where's", "which", "while", "who", "who's", "whom", "why", "why's", "with", "won't", "would", "wouldn't", "you", "you'd", "you'll", "you're", "you've", "your", "yours", "yourself", "yourselves", ' ');
	
	$tokensCount = count($tokens);	// counting tokens
		
	// Performing actions of each token
	for($k=0;$k<=$tokensCount;$k++)
	{
		$word = $tokens[$k];
		if(in_array($word, $stopWords))
		{
			unset($tokens[$k]);
		} // END if			
	}//END of tokenCount loop
	
	$tokens = array_values($tokens);
	return $tokens;
}


// This function remove numerical words from tokens
function removeNumericTokens($tokens)
{
	$tokensCount = count($tokens);	// counting tokens
		
	// Performing actions of each token
	for($k=0;$k<=$tokensCount;$k++)
	{
		$word = $tokens[$k];
		if(is_numeric($word) || empty($word) || !ctype_alpha($word))
		{
			unset($tokens[$k]);
		} // END if			
	}//END of tokenCount loop
	
	$tokens = array_values($tokens);
	return $tokens;
}


// Tokenization of query term
function makeQueryTokens($query)
{
	$t = explode(" ", $query);
	return $t;
}


function doSearch($word)
{
	$final_index = file_get_contents("final_index.json");
	$final_arr = json_decode($final_index,true);

	if(array_key_exists($word, $final_arr))
	{
		return $final_arr[$word];
	}
	else
	{
		return false;
	}	
}

// This function is mering query terms arrays in to one array
// Function used in queryManager.php
function mergeArrays($main,$temp)
{
	foreach ($temp as $key=>$val)
	{
		if(array_key_exists($key, $main) == TRUE)
		{
			$main[$key]['tf'] = $main[$key]['tf'] + $temp[$key]['tf'];
			$main[$key]['df'] = $main[$key]['df'] + $temp[$key]['df']; 
		}
		else
		{
			$main[$key]['tf'] = $temp[$key]['tf'];
			$main[$key]['df'] = $temp[$key]['df'];
		}	
	}
	return $main;		
}

// This function calculated the bm25 scores of the document against givent query
function BM25($df,$tf,$did)
{	
	$content = file_get_contents("content/".$did.".txt"); // reading giving document
	$doc_length = strlen($content); // length of the giving document
	
	$BM25 = 0; // initializing bm25 score variable
	$tfWeight = 1; // Term frequency weightage
	$dlWeight = 0.5; // Document frequency weightage
	$total_docs = 21578; // Total number of documents in the corpus
	$avg_dl = 123; // average document length of corpus
	
	$idf = log($total_docs/$df);
    $num = ($tfWeight + 1) * $tf;
    $denom = $tfWeight * ((1 - $dlWeight) + $dlWeight * ($doc_length / $avg_dl)) + $tf;
    $score = $idf * ($num/$denom);
     
	return $score;
}
?>