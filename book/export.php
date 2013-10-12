<?
	include_once "utilityMethods.php";
	
	$bookId		= $_REQUEST["book_id"];
	$result 	= getConnectedContentsByType("chapter", $bookId);		
	while (list ($id, $data) = mysql_fetch_row ($result))
	{
		print (".." . getValueFromString("name", $data) . "<br/>");
		print (lineBreaksToBr(getValueFromString("text", $data) . "<br/>"));
		print("<br/>");
	}
?>