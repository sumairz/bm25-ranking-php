<html>

<head>
<title>Reuters search engine</title>
</head>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="style.css">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/autocomplete.js"></script>

<script type="text/javascript">

function getResult() 
{
	var searchTerm = $("#tags").val();
	
	if(searchTerm.length > 0)
	{
		$("#content").css('background-color','#FFF');
		$("#content").html('');
		
		$("#result").html('<img src="loader.gif">')
		
		var stringData = "keyword="+searchTerm;
			
		$.ajax({
			type: 'POST',
			url: "queryManager.php",
			data: stringData,
			success: function(data) 
			{
				$("#result").html('<pj>'+data+'</p>');
			},
			error: function(data)
			{
				$("#result").html('<p style="color:red;">Error: '+data+'</p>');
			}
		});
	}
	else
	{
		$("#result").html('<p style="color:red;">Enter a query to search</p>');
	}
}

function showContent(docID)
{
	var keyword = $('#tags').val();
	var stringData = "docID="+docID+"&keyword="+keyword;
	
	$.ajax({
		type: 'POST',
		url: "getContent.php",
		data: stringData,
		success: function(data) 
		{
			$("#content").css('background-color','#F5F5F5');
			$("#content").html('<p>'+data+'</p>');
		},
		error: function(data)
		{
			$("#result").html('<p style="color:red;">Error: '+data+'</p>');
		}
	});
}

</script>

<body>

<h2>Reuters Search Engine</h2>
<div class="ui-widget">
<input id="tags" size="50" name="query" type="text"/>
</div>
<span id="faux" style="display:none;"></span><br>
<!-- <input type="text" name="query" id="query"> --> 
<input type="submit" name="Search" id="submit" value="Search" onclick="getResult()">

<div id="result"></div>
<div id="content"></div>

<!-- <div id="copyright">Developed by Syed Sumair Zafar <br /> Course Code: Comp 6791 <br /> <a href="https://github.com/sumairz/bm25-ranking-php">GitHub Repo</a> <br /> 2014</div>  -->
</body>
</html>