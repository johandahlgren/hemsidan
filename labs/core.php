<?php
	session_start();
	header('content-type: text/html; charset=utf-8');
	import_request_variables('GPC', '');
	$db = mysql_connect("localhost", "dahlgren_user", "ngw0bvxj");
	mysql_select_db("dahlgren_db", $db);
	
	//--------------------
	// Database functions
	//--------------------
	
	function dbGetSingleRow($aSqlQuery)
	{
		$temp 	= mysql_query($aSqlQuery);
		$result = null;
	
		if ($temp != null)
		{
			if (mysql_num_rows($temp) > 0)
			{
				$result = mysql_fetch_array(mysql_query($aSqlQuery)) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
			}
		}
		return $result;
	}
	
	function dbGetMultipleRows($aSqlQuery)
	{
		$result = mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
		return $result;
	}
	
	function dbExecuteQuery($aSqlQuery)
	{
		mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
	}
?>