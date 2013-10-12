<?php
	//---------------------------
	// Headers
	//---------------------------

	import_request_variables("GPC", "");

	//----------------
	// DB connection
	//---------------
	
	$db = mysql_connect("localhost", "dahlgren_user", "ngw0bvxj");
	mysql_select_db("dahlgren_db", $db);
	
	//----------------
	// Login / logout
	//----------------
		
	if ($_REQUEST["login"] == "true")
	{
		header("Location: " . getCmsUrl() . "/login.php");
	}
	else if ($_REQUEST["userAction"] == "login")
	{
		$sqlQuery = "SELECT password FROM j3_user WHERE user_name = '" . $_REQUEST["cmsUserId"] . "';";
		$result 		= dbGetSingleRow($sqlQuery);
		$passwordFromDb = $result[0];
				
		if ($passwordFromDb == $_REQUEST["cmsPassword"])
		{
			$_SESSION["userIsLoggedIn"] = true;
		}
	}
	else if ($_REQUEST["userAction"] == "logout")
	{
		$_SESSION["userIsLoggedIn"] = false;
	}
	
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
				$result = mysql_fetch_array(mysql_query($aSqlQuery)) or die("An error occured when executing the query: " . $aSqlQuery . " " . mysql_error());
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
	
	function countEntities($aEntityTypes, $aCategories, $aSiteId)
	{
		$entityTypeString = "type IN (";
		
		foreach($aEntityTypes as $key => $value) 
		{
			$entityTypeString = $entityTypeString . "'" . $value . "',";				
		}
		
		$entityTypeString 	= substr($entityTypeString, 0, strlen($entityTypeString) - 1);
		$entityTypeString 	= $entityTypeString . ")";
				
		if ($aCategories != null && count($aCategories) > 0)
		{
			$categoryString = " AND categoryId IN (";
			
			foreach($aCategories as $key => $value) 
			{
				$categoryString = $categoryString . "'" . $value . "',";				
			}
			
			$categoryString 	= substr($categoryString, 0, strlen($categoryString) - 1);
			$categoryString 	= $categoryString . ")";
		}
		$sqlQuery 			= "SELECT * FROM j3_entity e LEFT JOIN j3_entityCategory ec ON ec.entityId = e.id WHERE $entityTypeString $categoryString AND siteId = '$aSiteId' GROUP BY e.id;";		
		$result				= dbGetMultipleRows($sqlQuery);

		return mysql_num_rows($result);
	}
	
	function getConfigProperty($aPropertyName)
	{
		//error_log("Letar efter properties på: " . $_SESSION["rootPath"] . "config.properties");
		$properties = parse_ini_file($_SESSION["rootPath"] . "config.properties");
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
	
	function displayWysiwyg()
	{
		if(userIsLoggedIn && (getClientDevice() != "android" && getClientDevice() != "iphone"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function userIsLoggedIn()
	{
		return $_SESSION["userIsLoggedIn"];
	}
	
	function getClientDevice()
	{
		$device = '';
 
		if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) 
		{
			$device = "ipad";
		} 
		else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) 
		{
			$device = "iphone";
		} 
		else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) 
		{
			$device = "blackberry";
		} 
		else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) 
		{
			$device = "android";
		}
		
		return $device;
	}
		
	function getYMD($aDate)
	{
		setlocale(LC_TIME, "sv_SE");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);
			
		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%A %e %B %Y", $aDate);
		}
		else
		{
			$dateString = strftime("%A %e %b", $aDate);
		}
				
		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}
	
	function getYMDShort($aDate)
	{
		setlocale(LC_TIME, "sv_SE");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);
			
		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%e %b %Y", $aDate);
		}
		else
		{
			$dateString = strftime("%e %b", $aDate);
		}
			
		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}
	
	function getYMDH($aDate)
	{
		setlocale(LC_TIME, "sv_SE");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);
			
		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%A %e %B %Y %R", $aDate);
		}
		else
		{
			$dateString = strftime("%A %e %B %R", $aDate);
		}
				
		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}
	
	function getYMDHShort($aDate)
	{
		setlocale(LC_TIME, "sv_SE");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);
			
		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%a %e %b %Y %R", $aDate);
		}
		else
		{
			$dateString = strftime("%a %e %b %R", $aDate);
		}
				
		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}

	function getCmsUrl()
	{
		return getConfigProperty("cmsUrl");
	}
	
	//----------------------
	// Render methods
	//----------------------
	
	function renderComponent($aComponentName)
	{
		if ($aComponentName != null && $aComponentName != "")
		{
			$componentCode = getFileContent($_SESSION["rootPath"] . "site/" . "components/" . $aComponentName . ".php", true);
			if ($componentCode != null)
			{
				eval($componentCode);
			}
		}
		else
		{
			print "<p style=\"color: darkred;\">No component name provided!</p>";
		}
	}
	
	function includeComponent($aComponentName)
	{
		include($_SESSION["rootPath"] . "site/" . "components/" . $aComponentName . ".php");
	}

	
	function renderCmsComponent($aComponentName)
	{
		$componentCode = getFileContent(getConfigProperty("cmsPath") . "components/" . $aComponentName . ".php", true);
		if ($componentCode != null)
		{
			eval($componentCode);
		}
	}
	
	function includeForm($aType, $data)
	{
		include($_SESSION["rootPath"] . "site/" . "forms/" . $aType . "_form.php"); 
	}
	
	function includeRequired()
	{
		?>
			<link rel="stylesheet" type="text/css" href="<?php print getConfigProperty("cmsPath") ?>/style/admin.css" media="screen" />
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
			<script type="text/javascript" src="<?php print getConfigProperty("cmsPath") ?>/javascript/javascript.php"></script>
			<?php
				if (displayWysiwyg())
				{
					?>
						<script type="text/javascript" src="<?php print getConfigProperty("cmsPath") ?>/javascript/nicEdit.js"></script>
					<?php
				}
			?>
		<?php
	}
	
	function renderAdminButton($aId)
	{
		if (userIsLoggedIn())
		{
			?>
				<div class="adminButton openAdminButton" onclick="displayAdminPanel(<?php print $aId ?>);">Admin</div>
			<?php
		}
	}
	
	function renderNewButton($aParentId, $aType, $aAdminDiv, $aDivToRefresh) 
	{
		if (userIsLoggedIn())
		{
			?>
				<div class="adminButton buttonNew" title="Skapa nytt inneh&aring;ll av typen &quot;<?php print $aType ?>&quot;" onclick="openAdmin('<?php print getConfigProperty("cmsPath") ?>/formHandler.php?userAction=insert&amp;parentId=<?php print $aParentId ?>&amp;type=<?php print $aType ?>', '<?php print $aAdminDiv ?>', '<?php print $aDivToRefresh ?>');">Ny</div>
			<?php
		}
	}
	
	function renderEditButton($aEntityId, $aType, $aAdminDiv, $aDivToRefresh, $aHideOriginalDiv)
	{
		if (userIsLoggedIn())
		{
			?>
				<div class="adminButton buttonEdit" title="Redigera inneh&aring;ll" onclick="openAdmin('<?php print getConfigProperty("cmsPath") ?>/formHandler.php?userAction=update&amp;entityId=<?php print $aEntityId ?>&amp;type=<?php print $aType ?>', '<?php print $aAdminDiv ?>', '<?php print $aDivToRefresh ?>', <?php print $aHideOriginalDiv ?>);">Ändra</div>
			<?php
		}
	}
	
	function renderDeleteButton($aEntityId, $aDivId)
	{
		if (userIsLoggedIn())
		{
			?>
				<div class="adminButton buttonDelete" onclick="ajaxDelete(<?php print $aEntityId ?>, '<?php print $aDivId ?>');" title="Ta bort inneh&aring;llet.">Ta bort</div>
			<?php
		}
	}
	
	function renderSaveButton()
	{
		if (userIsLoggedIn())
		{
			?>
				<div id="saveButton" class="adminButton submitButton" onclick="ajaxSave();">
					<div id="loadingLayer"></div>
					<div id="resultDivContainer">
						<div id="resultDiv"></div>
					</div>
					Spara
				</div>
			<?php
		}
	}
	
	function renderCloseButton()
	{
		if (userIsLoggedIn())
		{
			?>
					<div class="adminButton closeButton" onclick="closeAdmin();">Stäng</div>
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
		$sqlQuery 	= "SELECT id, name, type, parentId, UNIX_TIMESTAMP(publishDate), sortOrder, data FROM j3_entity WHERE id = " . $aEntityId . " AND siteId = '" . getConfigProperty("siteId") . "';";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result;
	}
	
	function getEntities($aParentId, $aType, $aCategory, $aSortOrder1 = DESC, $aSortOrder2 = DESC)
	{
		
		$notFirstCriteria = false;
		
		if ($aCategory != null)
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate), ent.sortOrder, ent.data, entCat.categoryId FROM j3_entity ent LEFT JOIN j3_entityCategory entCat ON entCat.entityId = ent.id WHERE entCat.categoryId = " . $aCategory . " AND";
		}
		else
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate), ent.sortOrder, ent.data FROM j3_entity ent WHERE";
		}
		
		if ($aParentId != null)
		{
			$sqlQuery = $sqlQuery . " ent.parentId = " . $aParentId;
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
		
		$sqlQuery 	= $sqlQuery . " AND ent.siteId = '" . getConfigProperty("siteId") . "' ORDER BY sortOrder " . $aSortOrder1 . ", id " . $aSortOrder2 . ";";
		$result 	= dbGetMultipleRows($sqlQuery);

		return $result;
	}
	
	function getCategoryName($aCategoryId)
	{
		$sqlQuery 	= "SELECT name FROM j3_category WHERE id = " . $aCategoryId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}
	
	function getUsedTags($aSiteId)
	{
		$sqlQuery 	= "SELECT cat.id, cat.name, COUNT(cat.id) AS count FROM j3_category cat, j3_entityCategory ec, j3_entity e WHERE ec.categoryId = cat.id AND ec.entityId = e.id AND e.siteId='$aSiteId' GROUP BY cat.name ORDER BY count DESC;";
		$result 	= dbGetMultipleRows($sqlQuery);
		return $result;
	}
	
	function getAllTags($aSiteId)
	{
		$sqlQuery 	= "SELECT cat.id, cat.name FROM j3_category cat WHERE cat.siteId = '$aSiteId' ORDER BY name ASC;";
		$result 	= dbGetMultipleRows($sqlQuery);
		return $result;
	}
	
	function countTags($aSiteId)
	{
		$sqlQuery 	= "SELECT COUNT(cat.id) FROM j2_category cat, j2_content_category cc, j2_content con WHERE cc.category_id = cat.id AND cc.content_id = con.id AND con.site_id = '$aSiteId';";
		$result		= dbGetSingleRow($sqlQuery);
		return $result[0];
	}
	
	//----------------------
	// Persistence methods
	//----------------------

	function createEntity($aParentId, $aType)
	{
		if ($aParentId == null)
		{
			$aParentId = 0;
		}
		$dataString = requestToDataArray();
		$sqlQuery 	= "INSERT INTO j3_entity (name, type, parentId, siteId, sortOrder, publishDate, data) VALUES('" . getValueFromString("title", $dataString) . "', '" . $aType . "', " . $aParentId . ", '" . getConfigProperty("siteId") . "', 0, NOW(), '" . $dataString . "');";
		dbExecuteQuery($sqlQuery); 
		
		$sqlQuery2 		= "SELECT MAX(id) FROM j3_entity;"; 
		$result 		= dbGetSingleRow($sqlQuery2);
		$newEntityId	= $result[0];

		saveCategories($newEntityId, $dataString);
		
		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Entity created successfully";
		$responseArray["entityId"]	= $newEntityId;
		$dataArray["response"] 		= $responseArray;
		
		return $dataArray;
	}
	
	function saveEntity($aEntityId)
	{
		$dataString = requestToDataArray(); 
		$sqlQuery 	= "UPDATE j3_entity SET name = '" . $_REQUEST["contentName"] . "', data = '" . $dataString . "' WHERE id = " . $aEntityId . ";";
		dbExecuteQuery($sqlQuery);

		saveCategories($aEntityId, $dataString);
		
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
	
	function createCategory ($siteId, $aCategoryName)
	{
		$sqlQuery 	= "INSERT INTO j3_category (siteId, name) VALUES('" . $siteId . "', '" . $aCategoryName . "');";
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
	
	function saveCategories($aEntityId)
	{
		$sqlQuery 	= "DELETE FROM j3_entityCategory WHERE entityId = " . $aEntityId . ";";
		dbExecuteQuery($sqlQuery);

		foreach ($_REQUEST as $key => $value)
		{
			if (stristr($key, "category_") > -1)
			{
				$categoryId = substr($key, 9);
				$sqlQuery 	= "INSERT INTO j3_entityCategory (entityId, categoryId) VALUES(" . $aEntityId . ", " . $categoryId . ");";
				dbExecuteQuery($sqlQuery);
			}
		}
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