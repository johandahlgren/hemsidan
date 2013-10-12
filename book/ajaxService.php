<?php
	ob_start( 'ob_gzhandler' );
	
	include_once "utilityMethods.php";
	include_once "itemMethods.php";
	
	if (handleLogin("normal"))
	{
		//---------------------------------------------------
		// Check if the user has access to the selected book
		//---------------------------------------------------
		
		$userHasAccess = true;
		
		if ($bookId != null && $bookId != "")
		{
			$sqlQueryAccess = "SELECT * FROM jb_user_book WHERE book_id = " . $bookId . " AND user_id = \"" . $_SESSION["_book_userId"] . "\";";										
			$resultAccess	= dbGetMultipleRows($sqlQueryAccess);
			
			if(mysql_num_rows($resultAccess) == 0)
			{
				$userHasAccess = false;
			}
		}
		
		//---------------------------------------------------------
		// Check if the selected item belongs to the selected book
		//---------------------------------------------------------
		
		$itemBelongsToBook = true;
		
		if ($itemId != $bookId && $itemId != null && $itemId != "")
		{
			$sqlQueryBelongs = "SELECT * FROM jb_connection WHERE (content_id_1 = " . $itemId . " AND content_id_2 = " . $bookId . ") OR (content_id_1 = " . $bookId . " AND content_id_2 = " . $itemId . ");";										
			$resultBelongs	= dbGetMultipleRows($sqlQueryBelongs);
			
			if(mysql_num_rows($resultBelongs) == 0)
			{
				$itemBelongsToBook = false;
			}
		}
		
		if (!$userHasAccess)
		{
			?>
				You do not have access to this book!
			<?php
		}
		else if (!$itemBelongsToBook)
		{
			?>
				This item does not belong to the selected book.<br/>
				Are you trying to spy on your fellow authors?<br/>
				<br/>
				Naughty, naughty!
			<?php
		}
		else
		{
			if ($sortOrder == null || $sortOrder == "")
			{
				$sortOrder = 0;
			}
			
			if ($_REQUEST["user_action"] == "new")
			{
				$dataArray = createNewItem($bookId, $parentId, $type, $_SESSION["_book_userId"]);
				print (json_encode($dataArray));
			}
			else if ($_REQUEST["user_action"] == "save")
			{
				$dataArray = saveItem($itemId);
				print (json_encode($dataArray));
			}
			else if ($_REQUEST["user_action"] == "delete")
			{
				$dataArray = deleteItem($itemId);
				print (json_encode($dataArray));
			}
			else if ($_REQUEST["user_action"] == "connect")
			{
				$connect 		= $_REQUEST["connect"];
				$conectTo 		= $_REQUEST["connect_to"];
				$description 	= $_REQUEST["connection_description"];
				$sqlQuery 		= "INSERT INTO jb_connection (content_id_1, content_id_2, description) VALUES(" . $connect . ", " . $conectTo . ", \"" . $description . "\");";
				dbExecuteQuery($sqlQuery);	

				$dataArray 					= array();
				$responseArray 				= array();
				$responseArray["code"]		= "0";
				$responseArray["message"] 	= "Connectioin added";
				$dataArray["response"] 		= $responseArray;
			}
			else if ($_REQUEST["user_action"] == "remove_connection")
			{
				$contentId1	= $_REQUEST["content_id_1"];
				$contentId2	= $_REQUEST["content_id_2"];
				$sqlQuery 	= "DELETE FROM jb_connection WHERE (content_id_1 = " . $contentId1 . " AND content_id_2 = " . $contentId2 . ") OR (content_id_2 = " . $contentId1 . " AND content_id_1 = " . $contentId2 . ");";
				dbExecuteQuery($sqlQuery);

				$dataArray 					= array();
				$responseArray 				= array();
				$responseArray["code"]		= "0";
				$responseArray["message"] 	= "Connection removed";
				$dataArray["response"] 		= $responseArray;
			}
			else if ($_REQUEST["user_action"] == "new_user")
			{
				$newUserId							= $_REQUEST["new_user_id"];
				$newPassword						= $_REQUEST["new_password"];
				
				$sqlQuery 	= "INSERT INTO jb_user (user_id, password) VALUES('" . $newUserId . "', '" . md5($newPassword) . "');";
				dbExecuteQuery($sqlQuery);	
				
				$_SESSION["_book_userIsLoggedIn"] 	= true;
				$_SESSION["_book_userId"] 			= $newUserId;
				$_SESSION["_book_password"] 		= $newPassword;
				
				$dataArray 					= array();
				$responseArray 				= array();
				$responseArray["code"]		= "0";
				$responseArray["message"] 	= "User created";
				$dataArray["response"] 		= $responseArray;
			}
			else if ($_REQUEST["user_action"] == "update_user")
			{
				$authorName							= $_REQUEST["author_name"];
				$newPassword						= $_REQUEST["new_password"];
				$wordsPerPage						= $_REQUEST["words_per_page"];
				$skin								= $_REQUEST["skin"];
				
				if ($newPassword != null && $newPassword != "")
				{
					$passwordString = ", password = \"" . md5($newPassword) . "\"";
				}
				
				$sqlQuery 	= "UPDATE jb_user SET name = \"" . $authorName . "\"" . $passwordString . ", words_per_page = " . $wordsPerPage . ", skin = \"" . $skin . "\" WHERE user_id = \"" . $_SESSION["_book_userId"] . "\";";
				dbExecuteQuery($sqlQuery);	
				
				$_SESSION["_book_userName"]			= $authorName;
				$_SESSION["_book_skin"]				= $skin;
				
				$dataArray 					= array();
				$responseArray 				= array();
				$responseArray["code"]		= "0";
				$responseArray["message"] 	= "Settings updated";
				$dataArray["response"] 		= $responseArray;
			}			
			else if ($_REQUEST["user_action"] == "move_item")
			{
				$direction 	= $_REQUEST["direction"];
				
				if ($direction == "up")
				{
					$sqlQuery1 				= "SELECT type, sort_order FROM jb_content WHERE id = " . $itemId . ";";
					$result 				= dbGetSingleRow($sqlQuery1);
					$selectedType			= $result[0];
					$selectedSortOrder		= $result[1];
					
					$sqlQuery2 				= "SELECT id, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $bookId . " AND c.type = \"" . $type . "\" WHERE sort_order < " . $selectedSortOrder . " ORDER BY sort_order DESC LIMIT 1;";
					$result2 				= dbGetSingleRow($sqlQuery2);
					$previousItemId			= $result2[0];
					$previousItemSortOrder	= $result2[1];

					$sqlQuery 				= "UPDATE jb_content c SET c.sort_order = " . $selectedSortOrder . " WHERE c.id = " . $previousItemId . ";";
					dbExecuteQuery($sqlQuery);
					
					$sqlQuery 				= "UPDATE jb_content c SET c.sort_order = " . $previousItemSortOrder . " WHERE c.id = " . $itemId . ";";
					dbExecuteQuery($sqlQuery);
				}
				else if ($direction == "down")
				{
					$sqlQuery1 				= "SELECT type, sort_order FROM jb_content WHERE id = " . $itemId . ";";
					$result 				= dbGetSingleRow($sqlQuery1);
					$selectedType			= $result[0];
					$selectedSortOrder		= $result[1];
					
					$sqlQuery2 				= "SELECT id, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $bookId . " AND c.type = \"" . $type . "\" WHERE sort_order > " . $selectedSortOrder . " ORDER BY sort_order ASC LIMIT 1;";
					$result2 				= dbGetSingleRow($sqlQuery2);
					$nextItemId				= $result2[0];
					$nextItemSortOrder		= $result2[1];

					$sqlQuery 				= "UPDATE jb_content c SET c.sort_order = " . $selectedSortOrder . " WHERE c.id = " . $nextItemId . ";";
					dbExecuteQuery($sqlQuery);

					$sqlQuery 				= "UPDATE jb_content c SET c.sort_order = " . $nextItemSortOrder . " WHERE c.id = " . $itemId . ";";
					dbExecuteQuery($sqlQuery);
				}
				
				$dataArray 					= array();
				$responseArray 				= array();
				$responseArray["code"]		= "0";
				$responseArray["message"] 	= "Item moved successfully";
				$dataArray["response"] 		= $responseArray;
				
				print (json_encode($dataArray));
			}
			else if ($_REQUEST["user_action"] == "display_list")
			{
				renderList($_REQUEST["list_type"], $_REQUEST["title"], $type, $itemId, $bookId, $_REQUEST["div_id"], false, false, false);
			}
			else if ($_REQUEST["user_action"] == "display_menu")
			{
				renderMenu($_REQUEST["list_type"], $_REQUEST["title"], $type, $itemId, $bookId, $_REQUEST["div_id"]);
			}
			else if ($_REQUEST["user_action"] == "display_book_list")
			{
				renderBookList($_REQUEST["user_id"]);
			}
			else if ($_REQUEST["user_action"] == "import_data")
			{
				$bookId 		= $_REQUEST["book_id"];
				$importedText 	= $_REQUEST["text"];
				$chapters 		= preg_split("/(^\.{2})|(\n\.{2})/", $importedText); 
				$successCounter = 0;
				$errorCounter	= 0;
				$i 				= 0;
				$chaptersInBook = array();
				
				//-------------------------------------------------------------
				// Get the id:s of all chapters in this book.
				// Used to check that we don't update chapters in other books.
				//-------------------------------------------------------------
				
				$checkResult 	= getConnectedContentsByType("chapter", $bookId);
				while (list ($tempId) = mysql_fetch_row ($checkResult))
				{
					$chaptersInBook[$i] = $tempId;
					$i = $i + 1;
				}
				
				print "<div id=\"detailsDiv\" style=\"display: none;\">";
				print "<table>";
  
				foreach ($chapters as $chapter)
				{ 
					$title 		= trim(substr($chapter, 0, strpos($chapter, "\n")));
					$text 		= trim(substr($chapter, strpos($chapter, "\n")));
					
					if ($title != "")
					{
						$sqlQuery 	= "SELECT id, data FROM jb_content WHERE name = \"" . $title . "\";";
						$result 	= dbGetSingleRow($sqlQuery);
						
						if ($result != null)
						{
							$id 	= $result[0];
							$data 	= $result[1];
							
							if (in_array($id, $chaptersInBook))
							{
								$dataArray 				= unserialize($data);
								$dataArray["data_text"] = $text;
								$newData 				= serialize($dataArray);
								$escapedDataString		= jEscape($newData);
								$sqlQuery 				= "UPDATE jb_content SET data = \"" . $escapedDataString . "\" WHERE id = " . $id . ";";
								dbExecuteQuery($sqlQuery);
								
								print "<tr><td><span style=\"color: rgb(0, 200, 0);\">OK</span></td><td>The chapter \"" . $title . " was imported correctly.</td></tr>";
								$successCounter = $successCounter + 1;
							}
							else
							{
								print "<tr><td><span style=\"color: red\">ERROR</span></td><td>The chapter \"" . $title . "\" was found, but not in this book.</td></tr>";
								$errorCounter = $errorCounter + 1;
							}
						}
						else
						{
							print "<tr><td><span style=\"color: red\">ERROR</span></td><td>The chapter \"" . $title . "\" was not found on the site.</td></tr>";
							$errorCounter = $errorCounter + 1;
						}
					}
					
					$strToken = strtok(".."); 
				} 
				print "</table>";
				print "</div>";
				if ($errorCounter > 0)
				{
					print "<div style=\"font-family: arial; display: block; float: left; border: 2px solid rgb(100, 0, 0); padding: 10px; background-color: rgb(255, 100, 100);\">";
				}
				else
				{
					print "<div style=\"font-family: arial; display: block; float: left; border: 2px solid rgb(0, 100, 0); padding: 10px; background-color: rgb(100, 255, 100);\">";
				}
				print "IMPORT FINISHED<br/><br/>";
				print $successCounter . " chapter(s) were imported correctly.<br/>";
				print $errorCounter . " chapter(s) failed to import.<br/><br/>";
				print "<a href=\"javascript: document.getElementById('detailsDiv').style.display = 'block';\">Show details</a><br/>";
				print "</div>";
				print "<p style=\"clear: both;\"><br/><a href=\"import.php?book_id=" . $bookId . "\">Back to import</a></p>";
			}		
		}
	}
	
	ob_end_flush();
?>