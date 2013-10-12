<?php
	include_once "utilityMethods.php";

	function createNewItem($aBookId, $aParentId, $aType, $aBookUserId)
	{
		$dataString 			= encodeDataArray();
		$sqlQuery2 				= "SELECT sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $aBookId . " AND c.type = \"" . $aType . "\" ORDER BY sort_order DESC LIMIT 1;";
		$result2 				= dbGetSingleRow($sqlQuery2);
		$maxSortOrder			= $result2[0];

		$sqlQuery 	= "INSERT INTO jb_content (type, data, sort_order) VALUES(\"" . $aType . "\", \"" . $dataString . "\", " . ($maxSortOrder + 1) . ");";
		dbExecuteQuery($sqlQuery);
		$sqlQuery2 	= "SELECT MAX(id) FROM jb_content;";
		$result 	= dbGetSingleRow($sqlQuery2);
		$newItemId	= $result[0];

		if ($type == "book")
		{
			$sqlQuery 	= "INSERT INTO jb_user_book (user_id, book_id) VALUES(\"" . $aBookUserId . "\", " . $newItemId . ");";
			dbExecuteQuery($sqlQuery);
		}
		else if ($aType != null && $aType != "")
		{
			$sqlQuery 	= "INSERT INTO jb_connection (content_id_1, content_id_2) VALUES(" . $newItemId . ", " . $aParentId . ");";
			dbExecuteQuery($sqlQuery);
			
			$sqlQuery2 	= "SELECT * FROM jb_connection WHERE (content_id_1 = " . $newItemId . " AND content_id_2 = " . $aBookId . ") OR (content_id_2 = " . $newItemId . " AND content_id_1 = " . $aBookId . ");";
			$result 	= dbGetMultipleRows($sqlQuery2);
			
			if ($result != null && mysql_num_rows($result) == 0)
			{
				$sqlQuery 	= "INSERT INTO jb_connection (content_id_1, content_id_2) VALUES(" . $newItemId . ", " . $aBookId . ");";
				dbExecuteQuery($sqlQuery);
			}
		}

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Item created successfully";
		$responseArray["itemId"]	= $newItemId;
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function saveItem($aItemId)
	{
		$dataString = encodeDataArray();
		$sqlQuery 	= "UPDATE jb_content SET name = \"" . $_REQUEST["content_name"] . "\", data = \"" . $dataString . "\" WHERE id = " . $aItemId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Save successful";
		$dataArray["response"] 		= $responseArray;
		return $dataArray;	
	}
	
	function deleteItem($aItemId)
	{
		$sqlQuery 	= "DELETE FROM jb_content WHERE id = " .$aItemId . ";";
		dbExecuteQuery($sqlQuery);
		
		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Delete successful";
		$dataArray["response"] 		= $responseArray;
		return $dataArray;	
	}
?>