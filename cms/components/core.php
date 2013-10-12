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
	
	//----------------------
	// Utility methods
	//----------------------
	
	function getConfigProperty($aPropertyName)
	{
		$properties = parse_ini_file($_SESSION["sitePath"] . "/site/config.properties");
		return $properties[$aPropertyName];
	}
	
	function getFileContent($aFileName, $aPrintError)
	{
		$fileHandle		= fopen($aFileName, 'r');

		if ($fileHandle == null)
		{
			if ($aPrintError == true)
			{
				renderErrorMessage("File not found: \"" . $aFileName . "\"");
			}
			return null;
		}
		else
		{
			$fileSize 		= filesize($aFileName);
			$fileContent	= fread($fileHandle, $fileSize);
			fclose($fileHandle);

			return $fileContent;
		}
	}
	
	function getValueFromString($aFieldName, $aValueString)
	{
		$unescapedDataValue		= $aValueString;
		$values 				= unserialize($unescapedDataValue);	
		return stripslashes(getValue($aFieldName, $values));
	}
	
	function getValue($aFieldName, $aValues)
	{
		$fieldValue = $aValues["data_" . $aFieldName];
		if ($fieldValue == null || $fieldValue == "" || $fieldValue == "NaN" || $fieldValue == "undefined")
		{
			$fieldValue = "";
		}
		return trim($fieldValue);
	}
	
	function requestToDataArray()
	{
		$dataArray 	= array();
		
		foreach($_REQUEST as $key => $value) 
		{
			if (strpos($key, "data_") === 0)
			{
				$dataArray[$key] = $value;
			}
		}
		
		$dataString	= serialize($dataArray);
		
		return $dataString;
	}
	
	function getCurrentEntity()
	{
		$currentEntity = $_REQUEST["entityId"];
		
		if ($currentEntity == null || $currentEntity == "")
		{
			$currentEntity = 0;
		}
				
		return $currentEntity;
	}	
	
	function userIsLoggedIn()
	{
		return true;
	}
	
	function includeComponent($aComponentName)
	{
		include(getConfigProperty("componentFolderPath") . "/" . $aComponentName . ".php");
	}
	
	function getYMDH($aDate)
	{
		setlocale(LC_TIME, "sv_SE");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);
								
		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%e %B %Y %R", $aDate); 
		}
		else
		{
			$dateString = strftime("%e %B %R", $aDate);
		}
		
		return $dateString;
	}
	
	//----------------------
	// Render methods
	//----------------------
	
	function renderComponent($aComponentName)
	{
		$componentCode = getFileContent(getConfigProperty("componentFolderPath") . "/" . $aComponentName . ".php", true);
		if ($componentCode != null)
		{
			eval($componentCode);
		}
	}
	
	function renderCmsComponent($aComponentName)
	{
		$componentCode = getFileContent(getConfigProperty("cmsPath") . "/components/" . $aComponentName . ".php", true);
		if ($componentCode != null)
		{
			eval($componentCode);
		}
	}
	
	function includeRequired()
	{
		?>
			<link rel="stylesheet" type="text/css" href="<?php print getConfigProperty("cmsPath") ?>/style/admin.css" media="screen" />
			<script type="text/javascript" src="<?php print getConfigProperty("cmsPath") ?>/javascript/jquery-latest.pack.js"></script>
			<script type="text/javascript" src="<?php print getConfigProperty("cmsPath") ?>/javascript/javascript.php"></script>
		<?php
	}
	
	function renderNewButton($aParentId, $aType) 
	{
		if (userIsLoggedIn())
		{
			?>
				<a class="thickbox adminbutton buttonnew" title="Skapa nytt inneh&aring;ll av typen &quot;<?php print $aType ?>&quot;" href="<?php print getConfigProperty("cmsPath") ?>/formHandler.php?user_action=insert&parent_id=<?php print $aParentId ?>&type=<?php print $aType ?>">Lägg till</a>
			<?php
		}
	}
	
	function renderEditButton($aEntityId, $aType)
	{
		if (userIsLoggedIn())
		{
			?>aaaa
				<a class="thickbox adminbutton buttonedit" title="Redigera inneh&aring;ll" href="<?php print getConfigProperty("cmsPath") ?>/formHandler.php?user_action=update&entity_id=<?php print $aEntityId ?>&type=<?php print $aType ?>">Ändra</a>
			<?php
		}
	}
	
	function renderDeleteButton($aEntityId)
	{
		if (userIsLoggedIn())
		{
			?>
				<a class="adminbutton buttondelete" href="javascript: deleteEntity(<?php print $aEntityId ?>);" title="Ta bort inneh&aring;llet.">Ta bort</a>
			<?php
		}
	}
	
	function renderErrorMessage($aMessage)
	{
		?>
			<div class="errorMessage"><?php print $aMessage ?></div>
		<?php
	}

	//----------------------
	// Entity methods
	//----------------------

	function getEntity($aEntityId)
	{
		$sqlQuery 	= "SELECT id, name, type, parentId, sortOrder, data FROM j3_entity WHERE id = " . $aEntityId . " AND siteId = '" . getConfigProperty("siteId") . "';";
		$result 	= dbGetSingleRow($sqlQuery);

		return $result;
	}
	
	function getEntities($aParentId, $aType, $aCategory)
	{
		
		$notFirstCriteria = false;
		
		if ($aCategory != null)
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, ent.publishDate, ent.sortOrder, ent.data, entCat.categoryId FROM j3_entity ent LEFT JOIN j3_entityCategory entCat ON entCat.entity_id = ent.id";
		}
		else
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, ent.publishDate, ent.sortOrder, ent.data FROM j3_entity ent WHERE";
		}
		
		if ($aParentId != null)
		{
			$sqlQuery = $sqlQuery . " ent.parent_id = " . $aParentId;
			$notFirstCriteria = true;
		}
		
		if ($aType != null)
		{
			if($notFirstCriteria == true)
			{
				$sqlQuery = $sqlQuery . " AND";
			}
			$sqlQuery = $sqlQuery . " ent.type = '" . $aType . "'";
			$notFirstCriteria = true;
		}
		
		$sqlQuery = $sqlQuery . " AND ent.siteId = '" . getConfigProperty("siteId") . "';";
		$result = dbGetMultipleRows($sqlQuery);

		return $result;
	}
	
	function countEntities($aParentId, $aType, $aCategory)
	{
		$result = getEntities($aParentId, $aType, $aCategory);
		return mysql_num_rows($result);
	}
	
	//----------------------
	// Persistence methods
	//----------------------

	function createEntity($aParentId, $aType)
	{
		$dataString = requestToDataArray();
		$sqlQuery 	= "INSERT INTO j3_entity (site, type, data, sortOrder) VALUES('" . getConfigProperty("siteId") . "', '" . $aType . "', '" . $dataString . "', 0);";
		dbExecuteQuery($sqlQuery);
		$sqlQuery2 	= "SELECT MAX(id) FROM jb_content;";
		$result 	= dbGetSingleRow($sqlQuery2);
		$newItemId	= $result[0];

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Entity created successfully";
		$responseArray["itemId"]	= $newItemId;
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function saveEntity($aItemId)
	{
		$dataString = requestToDataArray();
		$sqlQuery 	= "UPDATE j3_entity SET name = '" . $_REQUEST["content_name"] . "', data = '" . $dataString . "' WHERE id = " . $aItemId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Save successful";
		$dataArray["response"] 		= $responseArray;
		return $dataArray;	
	}
	
	function deleteEntity($aEntityId)
	{
		$sqlQuery 	= "DELETE FROM j3_entity WHERE id = " .$aEntityId . ";";
		dbExecuteQuery($sqlQuery);
		
		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Delete successful";
		$dataArray["response"] 		= $responseArray;
		return $dataArray;	
	}
	
	function createCategory ($aCategoryName, $aCategoryDescription)
	{
		$sqlQuery 	= "INSERT INTO j3_category (name, description) VALUES('" . $aCategoryName . "', '" . $aCategoryDescription . "';";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully created";
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function deleteCategory ($aCategoryId)
	{
		$sqlQuery 	= "DELETE FROM j3_category WHERE id = " .$aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully deleted";
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function addCategoryToEntity ($aCategoryId, $aEntityId)
	{
		$sqlQuery 	= "INSERT INTO j3_entityCategory (entityId, categoryId) VALUES(" . $aEntityId . ", " . $aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully added to the entity";
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function removeCategoryFromEntity ($aCategoryId, $aEntityId)
	{
		$sqlQuery 	= "DELETE FROM j3_entityCategory WHERE entity_id = " . $aEntityId . " AND category_id = " . $aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully removed from the entity";
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
?>