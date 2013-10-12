<?
	include_once "utilityMethods.php";
	
	$bookId		= $_REQUEST["book_id"];
	
	$sqlQuery 	= "SELECT id, data FROM jb_content WHERE id = " . $bookId . ";";
	$result 	= dbGetSingleRow($sqlQuery);
	$id 		= $result[0];
	$data		= $result[1];
?>
<html>
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body>
		<h1>Import text into the book "<?php print getValueFromString("name", $data) ?>"</h1>
		<form name="" action="ajaxService.php" method="post">
			<input type="hidden" name="user_action" value="import_data"/>
			<input type="hidden" name="book_id" value="<?php print $bookId ?>"/>
			<textarea name="text" rows="20" cols="50"></textarea><br/>
			<input type="submit" value="Import"/>
		</form>
	</body>
</html>