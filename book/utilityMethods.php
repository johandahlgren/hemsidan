<?php
session_start();
header('content-type: text/html; charset=utf-8');
import_request_variables('GPC', '');
$db = mysql_connect("localhost", "dahlgren_user", "ngw0bvxj");
mysql_select_db("dahlgren_db", $db);

$parentId 	= $_REQUEST["parent_id"];
$itemId 	= $_REQUEST["item_id"];
$bookId 	= $_REQUEST["book_id"];
$type 		= $_REQUEST["type"];
$userAction = $_REQUEST["user_action"];
$sortOrder 	= $_REQUEST["sort_order"];
$returnUrl	= $_REQUEST["return_url"];

$currentUrl	= "editContent.php?book_id=" . $bookId . "&parent_id=" . $parentId . "&item_id=" . $itemId . "&type=" . $type;

if ($bookId != null && $bookId != "")
{
	$_SESSION["_book_bookId"] = $bookId;
}
else if ($_SESSION["_book_bookId"] != null && $_SESSION["_book_bookId"] != "")
{
	$bookId = $_SESSION["_book_bookId"];
}

if ($sortOrder == null || $sortOrder == "")
{
	$sortOrder = 0;
}

if ($_REQUEST["user_action"] == "new_user")
{
	$newUserId							= $_REQUEST["new_user_id"];
	$newPassword						= $_REQUEST["new_password"];
	
	$sqlQuery 	= "INSERT INTO jb_user (user_id, password) VALUES('" . $newUserId . "', '" . md5($newPassword) . "');";
	dbExecuteQuery($sqlQuery);	
	
	$_SESSION["_book_userIsLoggedIn"] 	= true;
	$_SESSION["_book_userId"] 			= $newUserId;
	$_SESSION["_book_password"] 		= $newPassword;
	
	print "User created";
}
else if ($_REQUEST["user_action"] == "logout")
{
	session_unset();
	session_destroy();
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
	return trim(jUnescape($fieldValue));
}

function getConnectedContentsByType($aType, $aItemId)
{
	$typeSelection 	= "";
	$separator		= "";
	$types			= explode(",", $aType);
	
	for ($i = 0; $i < count($types);$i = $i + 1)
	{
		if ($i > 0)
		{
			$separator = " OR ";
		}
		$typeSelection 	= $typeSelection . $separator . "c.type = \"" . $types[$i] . "\"";
	}
	$sqlQuery 		= "SELECT id, data, sort_order, c.type, x.description FROM jb_content c, jb_connection x WHERE ((x.content_id_2 = c.id AND x.content_id_1 = " . $aItemId . ") OR (x.content_id_2 = " . $aItemId . " AND x.content_id_1 = c.id)) AND (" . $typeSelection . ") ORDER BY c.sort_order ASC, c.id ASC";
	$result 		= dbGetMultipleRows($sqlQuery);	
	return $result;
}
function createLinkDialog($aListType, $aTitle, $aType, $aItemId, $aBookId, $aConnectTo, $aDivId)
{
	$types			= explode(",", $aType);
	$identifier		= "";
	
	for ($i = 0; $i < count($types);$i = $i + 1)
	{
		if ($i > 0)
		{
			$separator = " OR ";
		}
		else
		{
			$identifier = $types[$i];
		}
		$typeSelection 	= $typeSelection . $separator . "c2.type = \"" . $types[$i] . "\"";
	}
	
	$sqlQuery			= "SELECT c2.id, c2.data FROM jb_user_book ub 
						LEFT JOIN jb_content c1 ON ub.book_id = c1.id 
						LEFT JOIN jb_connection con ON con.content_id_2 = c1.id 
						LEFT JOIN jb_content c2 ON con.content_id_1 = c2.id
						WHERE ub.user_id=\"" . $_SESSION["_book_userId"] . "\" 
						AND (" . $typeSelection . ") ORDER BY c2.sort_order ASC, c2.id ASC;";
	$result 			= dbGetMultipleRows($sqlQuery);	
	?>
		<div id="connect<?php print str_replace(",", "", $aType) ?>" class="connectdiv dropshadow" style="display: none;">
			<div class="contentblocktitle"><?php print $aTitle ?></div>
			<div class="contentcontainer">
				<div class="block">
					<input type="text" id="connectionDescription<?php print $identifier ?>" />
				</div>
				<table class="listtable">
					<tbody>
						<?php
							while (list ($id, $data) = mysql_fetch_row ($result))
							{
								if (getValueFromString("type", $data) == "category")
								{
									$rowClass = "listcategory";
								}
								else
								{
									$rowClass = "";
								}
								?>	
									<tr>
										<td class="<?php print $rowClass ?>">
											<a href="javascript: connectContent('<?php print $aListType ?>', '<?php print $aTitle ?>', '<?php print $aType ?>', <?php print $aItemId ?>, <?php print $aBookId ?>, <?php print $id ?>, <?php print $aConnectTo ?>, '<?php print $identifier ?>', '<?php print $aDivId ?>', '<?php print $aParameters ?>');">
												<?php 
													if (getValueFromString("short_name", $data))
													{
														print getValueFromString("short_name", $data);
													}
													else
													{
														print getValueFromString("name", $data);
													}
												?>
											</a>
										</td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="mainbuttons">	
				<div class="center">
					<a href="javascript: showHideDiv('connect<?php print str_replace(",", "", $aType) ?>')" class="button">Close</a>
				</div>
			</div>
		</div>
	<?php
}

function loadList($aDivId, $aParameters)
{
	?>
		<div id="<?php print $aDivId ?>" class="listdiv"></div>
		<script type="text/javascript">
			makeAjaxCall("<?php print $aDivId ?>", "<?php print $aParameters ?>");
		</script>
	<?php
}

function createContentBlock($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayCreateButton, $aDisplayLinkButton, $aDisplayUnlinkButton)
{
	?>
		<div class="list dropshadow">
			<?php
				if ($aListType != "menu" && $aListType != "")
				{
					?>
						<div class="contentblocktitle">
							<?php print $aTitle ?>
						</div>
					<?php
				}
			?>
			<div class="contentcontainer">
				<?php 
					createContentList($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayUnlinkButton); 
				?>
			</div>
			<?php
				if ($aListType == "menu" || $aListType == "list")
				{
					?>
					<div class="mainbuttons">
						<div class="center">
							<?php	
								if ($aListType == "menu")
								{
									?>
										<a href="<?php print $_SESSION["device"] ?>?user_action=new&amp;type=<?php print $aType ?>&amp;book_id=<?php print $aBookId ?>&amp;parent_id=<?php print $aItemId ?>" class="button"  title="Create a new <?php print $aType ?>.">New</a>
									<?php
								}
								
								if ($aListType == "list")
								{
									?>
										<div class="center">
											<?php if ($aDisplayLinkButton)
											{
												?>
													<a href="javascript: positionDivAtPointer('connect<?php print str_replace(",", "", $aType) ?>'); showHideDiv('connect<?php print str_replace(",", "", $aType) ?>')" class="button" title="Link an existing <?php print $aType ?> to this item.">Link</a>
												<?php
											}
											
											if ($aDisplayCreateButton)
											{
												?>
													<a href="<?php print $_SESSION["device"] ?>?user_action=new&amp;type=<?php print $aType ?>&amp;book_id=<?php print $aBookId ?>&amp;parent_id=<?php print $aItemId ?>" class="button"  title="Create a new <?php print $aType ?> and link it to this item.">Create</a>
												<?php
											}
										?>
										</div>
									<?php
								}
							?>
						</div>
					</div>
					<?php
				}
			?>
		</div>
	<?php
}

function createContentList($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayUnlinkButton)
{
	?>
		<table class="listtable">
			<?php
				if ($aType == "read")
				{
					$modifiedType = "chapter";
					
				}
				else
				{
					$modifiedType = $aType;
				}
				
				$counter 	= 1;
				$result 	= getConnectedContentsByType($modifiedType, $aItemId);		
				while (list ($id, $data, $sortOrder, $type, $description) = mysql_fetch_row ($result))
				{
					if ($id == $_REQUEST["item_id"])
					{
						$rowClass = "currentselection";
					}
					else
					{
						$rowClass = "";
					}
					
					if (getValueFromString("type", $data) == "category")
					{
						$rowClass = "listcategory";
					}
					
					if (getValueFromString("short_name", $data))
					{
						$linkText = getValueFromString("short_name", $data);
					}
					else
					{
						$linkText = getValueFromString("name", $data);
					}
					
					if ($linkText == null || $linkText == "") 
					{
						$linkText = "N/A";
					}
					?>
						<tr class="<?php print $rowClass ?> <?php print getValueFromString("row_style", $data) ?>">
							<?php 
								if (getValueFromString("type", $data) == "category")
								{
									?>
										<td class="fullwidth aligncenter">
									<?php
								}
								else
								{
									?>
										<td class="fullwidth">
									<?php
								}
								$itemLink = $_SESSION["device"] . "?type=" . $aType . "&amp;book_id=" . $aBookId . "&amp;parent_id=" . $aItemId . "&amp;item_id=" . $id;
								
								$itemImage = getValueFromString("image", $data);
								
								?>
								<div class="itemPreviewImageLink">
									<?php
										if ($itemImage != null && $itemImage != "")
										{
											?>
												<a href="<?php print $itemImage ?>" class="pirobox_gall" title="<?php print getValueFromString("name", $data) ?>"><img class="itemPreviewImage pirobox_gall" src="<?php print $itemImage ?>" title="<?php print getValueFromString("name", $data) ?>" alt="<?php print getValueFromString("name", $data) ?>"></a>
											<?php
										}
										else
										{
											?>
												<div class="itemPreviewImage" title="<?php print getValueFromString("name", $data) ?>">?</div>
											<?php
										}
									?>
								</div>
								<a href="<?php print $itemLink ?>" class="blocklink" title="<?php print getValueFromString("name", $data) ?>">
									<?php 
										print $counter . ": ";
										if (strlen($linkText) > 15)
										{
											print substr($linkText, 0, 15) . "…";
										}
										else
										{
											print $linkText;
										}
									
										$gender = getValueFromString("gender", $data);
										
										if ($gender != null && $gender != "")
										{
											if ($gender == "male")
											{
												print "<span class=\"gendermale\">&nbsp;&#9794;</span>";
											}
											else if ($gender == "female")
											{
												print "<span class=\"genderfemale\">&nbsp;&#9792;</span>";
											}
											else if ($gender == "mixed")
											{
												print "<span class=\"gendermale\">&nbsp;&#9794;</span><span class=\"genderfemale\">&nbsp;&#9792;</span>";
											}
										}
										
										if ($aListType == "menu" && getValueFromString("text", $data) != null && getValueFromString("text", $data) != "")
										{
											$wordArray 			= explode(" ", getValueFromString("text", $data));
											$numberOfWords 		= count($wordArray);
											if (strlen(getValueFromString("text", $data)) == 0)
											{
												$numberOfWords = 0;
											}
											?>
												<span class="numberofwords">
													<?php print $numberOfWords; ?>
												</span>
											<?php
										}
									?>
								</a>
							</td>
							<td>
								<?php
									if ($description != null && $description != "")
									{
										?>
											
												<img class="infoicon" title="<?php print $description ?>" alt="<?php print $description ?>">
											
										<?php
									}
								?>
							</td>
							<?php
								if ($aListType == "menu")
								{
									?>
										<td>
											<div class="movediv">
												<?php
													if ($counter > 1)
													{
														?>
															<a href="javascript: moveItem(<?php print $id ?>, 'up', '<?php print $aDivId ?>');" class="buttonup blocklink" onclick="cancelBubbling();" title="Move item up">&#8743;</a>
														<?php
													}
												
													if ($counter < mysql_num_rows($result))
													{
														?>
															<a href="javascript: moveItem(<?php print $id ?>, 'down', '<?php print $aDivId ?>');" class="buttondown blocklink" onclick="cancelBubbling();" title="Move item down">&#8744;</a>
														<?php
													}
												?>
											</div>
										</div>
									<?php
								}
							
								if ($aListType == "list" && $aDisplayUnlinkButton == true)
								{
									?>
										<td class="paddingright">
											<a href="javascript: removeConnection('<?php print $aListType ?>', '<?php print $aTitle ?>', '<?php print $aType ?>', <?php print $aItemId ?>, <?php print $aBookId ?>, <?php print $id ?>, <?php print $aItemId ?>, '<?php print $aDivId ?>')" class="blocklink delete" onclick="cancelBubbling();" title="Remove the connection to this item. The item itself will NOT be removed.">X</a>&nbsp;
										</td>
									<?php
								}
							?>
						</tr>
					<?php
					$counter = $counter + 1;
				}
				if (mysql_num_rows($result) == 0)
				{
					?>
						<tr>
							<td>
								<div class="smallpadding">None</div>
							</td>
						</tr>
					<?php
				}
			?>
		</table>
	<?php
}

function createSimpleContentList($aTitle, $aType, $aItemId, $aBookId)
{
	?>
	<div class="fieldname dropshadow"><?php print $aTitle ?>: </div>
	<?php
	$result = getConnectedContentsByType($aType, $aItemId);		
	while (list ($id, $data) = mysql_fetch_row ($result))
	{
		$imageUrl = getValueFromString("image", $data);
		?>
			<div class="left">
				<a href="<?php print $_SESSION["device"] ?>?type=<?php print $aType ?>&book_id=<?php print $aBookId ?>&parent_id=<?php print $aItemId ?>&item_id=<?php print $id ?>">
					<?php
						if ($imageUrl != null && $imageUrl != "")
						{
							?>
								<img class="smallimage" src="<?php print $imageUrl ?>"><br/>
							<?php
						}
						else
						{
							?>
								<img class="smallimage" src="images/unknown_person.jpg"><br/>
							<?php
						}
					?>
					<?php print getValueFromString("name", $data) ?>
				</a>
				<a href="javascript: removeConnection(<?php print $id ?>, <?php print $aItemId ?>)" class="delete">X</a>&nbsp;
			</div>
		<?php
	}
	createLinkDialog($aTitle, $aType, $aItemId);
}

function createImageList($aType, $aItemId)
{
		$result 	= getConnectedContentsByType($aType, $aItemId);		
		while (list ($id, $data, $sortOrder, $type, $description) = mysql_fetch_row ($result))
		{	
			$itemImage = getValueFromString("image", $data);
			?>
			<div class="itemPreviewImageLink">
				<?php
					if ($itemImage != null && $itemImage != "")
					{
						?>
							<a href="<?php print $itemImage ?>" class="pirobox_gall" title="<?php print getValueFromString("name", $data) ?>"><img class="itemPreviewImage pirobox_gall" src="<?php print $itemImage ?>" title="<?php print getValueFromString("name", $data) ?>" alt="<?php print getValueFromString("name", $data) ?>"></a>
						<?php
					}
					else
					{
						?>
							<div class="itemPreviewImage" title="<?php print getValueFromString("name", $data) ?>">?</div>
						<?php
					}
				?>
			</div>
			<?php
		}
		if (mysql_num_rows($result) == 0)
		{
			?>
				<div class="smallpadding">None</div>
			<?php
		}
}

function renderOverview($aType, $aItemId, $aBookId)
{
	?>
		<div class="overviewdiv">
			<?php
				$result 	= getConnectedContentsByType($aType, $aItemId);		
				while (list ($id, $data, $sortOrder, $type, $description) = mysql_fetch_row ($result))
				{
					if (getValueFromString("short_name", $data))
					{
						$linkText = getValueFromString("short_name", $data);
					}
					else
					{
						$linkText = getValueFromString("name", $data);
					}
					
					if ($linkText == null || $linkText == "")  
					{
						$linkText = "N/A";
					}
					
					if (getValueFromString("type", $data) == "category")
					{
						?>
							<div class="category">
								<?php print $linkText ?>
							</div>
						<?php
					}
					else
					{
						?>
							<a href="<?php print $_SESSION["device"] ?>?type=<?php print $type ?>&book_id=<?php print $aBookId ?>&parent_id=<?php print $aItemId ?>&item_id=<?php print $id ?>">
								<div class="overviewitem">
									<?php
										$image = getValueFromString("image", $data);
										if ($image != null && $image != "")
										{
											?>
												<img src="<?php print $image ?>"/>
											<?php
										}
										else
										{
											?>
												<!--<img class="unknownperson dropshadow"/>-->
												<div class="unknownperson">?</div>
											<?php
										}
									?>
									<div class="paddingtop aligncenter">
										<?php 
											$text = getValueFromString("short_name", $data);
											if ($text == null || $text == "")
											{
												$text = getValueFromString("name", $data);
											}
											print $text;
											
											$gender = getValueFromString("gender", $data);
								
											if ($gender != null && $gender != "")
											{
												if ($gender == "male")
												{
													print "<span class=\"gendermale\">&nbsp;&#9794</span>";
												}
												else if ($gender == "female")
												{
													print "<span class=\"genderfemale\">&nbsp;&#9792</span>";
												}
												else if ($gender == "mixed") 
												{
													print "<span class=\"gendermale\">&nbsp;&#9794</span><span class=\"genderfemale\">&nbsp;&#9792</span>";
												}
											}
										?>
									</div>								
								</div>
							</a>
						<?php
					}
				}
			?>
			<div class="overviewitem">
				<a href="<?php print $_SESSION["device"] ?>?user_action=new&type=<?php print $aType ?>&book_id=<?php print $aBookId ?>&parent_id=<?php print $aItemId ?>" title="Create a new <?php print $aType ?>.">
					<div class="unknownperson">
						+
					</div>
					<div class="paddingtop aligncenter">
						Add
					</div>
				</a>
			</div>
		</div>
	<?php
}

function renderBookList($aUserId, $aNextType)
{
	?>
		<div id="leftMenuDiv">
			<div class="list dropshadow">
				<div class="contentcontainer">
					<table class="listtable">
						<?php
							$sqlQuery 	= "SELECT id, data FROM jb_user_book ub LEFT JOIN jb_content c ON ub.user_id = \"" . $aUserId . "\" AND ub.book_id = c.id WHERE type = \"book\";";
							$result 	= dbGetMultipleRows($sqlQuery);

							while (list ($id, $data) = mysql_fetch_row ($result))
							{
								?>
									<tr class="<?php print $rowClass ?> <?php print getValueFromString("row_style", $data) ?>">
										<td>
											<a href="<?php print $_SESSION["device"] ?>?type=<?php print $aNextType ?>&book_id=<?php print $id ?>&parent_id=<?php print $id ?>&item_id=<?php print $id ?>" class="blocklink"><?php print getValueFromString("name", $data) ?></a>
										</td>
									</tr>
								<?php
							}
						?>
					</table>
				</div>
				<div class="mainbuttons">
					<div class="center">
						<a href="<?php print $_SESSION["device"] ?>?user_action=new&type=book" class="button"  title="Create a new book.">New</a>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function renderPrintList($bookId, $parentId)
{
	?>
		<div id="leftMenuDiv">
			<div class="list dropshadow">
				<div class="contentblocktitle">Chapters</div>
				<div class="contentcontainer">
					<table class="listtable">
						<?php
							$sqlQuery 		= "SELECT id, data, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $_SESSION["bookId"] . " AND c.type = \"chapter\" ORDER BY c.sort_order ASC, c.id ASC;";
							$result 		= dbGetMultipleRows($sqlQuery);
							$counter 		= 1;
							
							while (list ($id, $data, $sortOrder) = mysql_fetch_row($result))
							{
								if (getValueFromString("name", $data) != null && getValueFromString("name", $data) != "")
								{
									$name = getValueFromString("name", $data); 
								}
								else
								{
									$name = "[Unknown]";
								}
								
								if ($id == $_REQUEST["item_id"])
								{
									$rowClass = "currentselection";
								}
								else
								{
									$rowClass = "";
								}
								?>
									<tr class="<?php print $rowClass ?>">
										<td class="alignright">
											<?php print $counter ?>:
										</td>
										<td class="fullwidth">
											<a href="index.php?type=printView&book_id=<?php print $bookId ?>&parent_id=<?php print $parentId ?>&item_id=<?php print $id ?>&scope=<?php print $scope ?>&chapter_number=<?php print $counter ?>">
												<?php
													if (strlen($name) > 15)
													{
														print substr($name, 0, 15) . "…";
													}
													else
													{
														print $name;
													}
												?>
											</a><br/>
										</td>
										<td class="alignright">
											<?php
												$text 				= getValueFromString("text", $data);
												$wordArray 			= explode(" ", $text);
												$numberOfWords 		= count($wordArray);
												print $numberOfWords . "&nbsp;";
											?>
										</td>
									</tr>
								<?php
								$counter = $counter + 1;
							}
						?>
					</table>
				</div>
				<div class="mainbuttons">
					<div class="center">
						<a href="forms/draft.php?book_id=<?php print $bookId ?>#chapter<?php print $itemId ?>" onclick="this.target = '_blank'" class="button" title="Display the entire book as simple text.">Print View</a>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function jEscape($aString)
{	
	return mysql_real_escape_string($aString);
}

function jUnescape($aString)
{
	return ($aString);
}

function handleLogin($aType)
{
	if ($_SESSION["_book_userIsLoggedIn"] != true)
	{
		if ($_REQUEST["user_id"] != null && $_REQUEST["password"] != null)
		{
			$userId 	= $_REQUEST["user_id"];
			$password 	= $_REQUEST["password"];
		}
		
		$sqlQuery 		= "SELECT password, name, skin FROM jb_user WHERE user_id = \"" . $userId . "\";";
		$result 		= dbGetSingleRow($sqlQuery);
		$dbPassword 	= $result[0];
		$dbName 		= $result[1];
		$dbSkin 		= $result[2];

		if (md5($password) === $dbPassword)
		{
			$_SESSION["_book_userIsLoggedIn"] 	= true;
			$_SESSION["_book_userId"] 			= $userId;
			$_SESSION["_book_userName"] 		= $dbName;
			$_SESSION["_book_password"] 		= $password;
			$_SESSION["_book_skin"]				= $dbSkin;
			return true;
		}
		else
		{
			?>
				<input type="hidden" name="user_action" value="login">
				<div class="center">
					<?php
						if ($_REQUEST["new_user"] != "true")
						{
							?>
								<div class="list login dropshadow">
									<div class="contentblocktitle">
										Login
									</div>
									<div class="contentcontainer padding">
										<div class="block">
											User ID:<br/>
											<input type="text" id="user_id" name="user_id" value="<?php print $userId ?>"/>
										</div>
										<div class="block">
											Password<br/>
											<input type="password" name="password" />
										</div>
										<?php
											if ($userId != null && $password != null)
											{
												?>
													<div class="block error">Invalid user id or password</div>
												<?php
											}
										?>
									</div>
									<div class="mainbuttons"> 
										<div class="center">
											<a href="javascript: document.inputForm.action = '<?php print $_SESSION["device"] ?>'; document.inputForm.submit()" class="button ">Log in</a> 
										</div>
									</div>
								</div>
								<?php
									if ($aType == "normal")
									{
										?>
											<div class="block center aligncenter">
												or <br/>
												<a href="<?php print $_SESSION["device"] ?>?new_user=true" class="button dropshadow">Create a new user</a> 
											</div>
										<?php
									}
								?>
								<script type="text/javascript">
									$("#user_id").focus();
								</script>
							<?
						}
						else
						{
							?>
								<div class="list login">
									<h1>Create new user</h1>
									<div class="padding">
										<div class="block">
											User ID:<br/>
											<input type="text" name="new_user_id"/>
										</div>
										<div class="block">
											Password<br/>
											<input type="password" name="new_password" />
										</div>
									</div>
									<div class="block mainbuttons">
										<div class="center">
											<a href="javascript: createNewUser();">Create user</a>
										</div>
									</div>
								</div>
								<div class="block center aligncenter">
									or<br/>
									<a href="index.php" class="button dropshadow">Login using an existing account</a>
								</div>
							<?php
						}
					?>
				</div>
			<?php
		}
	}
	else
	{
		return true;
	}
}

function printImage($aFieldName, $aImageUrl, $aTitle)
{
	if ($aImageUrl == null || $aImageUrl == "")
	{
		?>
			<img id="<?php print $aFieldName ?>" class="noimage"/>	
		<?php
	}
	else
	{
		?>
			<a href="<?php print $aImageUrl ?>" class="pirobox_gall" title="<?php print $aTitle ?>"><img id="<?php print $aFieldName ?>" src="<?php print $aImageUrl ?>" class="portrait" /></a>		
		<?php
	}
	?>
		<input type="text" class="fieldrightcol" name="data_<?php print $aFieldName ?>" value="<?php print $aImageUrl ?>" onblur="updateImage(this.value, '<?php print $aFieldName ?>');"/>
	<?php
}

function printTabWelcomeMessage($aType)
{
	printInfoMessage("Select the <span class=\"bold\">" . $aType . "</span> you want to work with in the menu on the left...<br/><br/>...or create a new one by clicking the \"New\" button.");
}

function printInfoMessage ($aMessage)
{
	?>
		<div id="infoDiv" class="dropshadow">
			<?php print $aMessage ?>
		</div>
	<?php
}

function renderListAndConnection($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayCreateButton)
{
	renderList($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayCreateButton, true, true);
	createLinkDialog($aListType, $aTitle, $aType, $aItemId, $aBookId, $aItemId, $aDivId);
}

function renderList($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayCreateButton, $aDisplayLinkButton, $aDisplayUnlinkButton)
{
	if ($aListType == "menu")
	{
		?>
			<script type="text/javascript">
				document.inputForm.elements["type"].value = "<?php print $aType ?>";
				document.inputForm.elements["title"].value = "<?php print $aTitle ?>";
			</script>
		<?php
	}
	?>
		<div id="<?php print $aDivId ?>">
			<?php
				createContentBlock($aListType, $aTitle, $aType, $aItemId, $aBookId, $aDivId, $aDisplayCreateButton, $aDisplayLinkButton, $aDisplayUnlinkButton);				
			?>
		</div>
	<?php
}

function renderButtons($aTitle, $aType, $aItemId, $aBookId, $aCurrentUrl, $aDisplayWritingStudioButton)
{
	?>
		<div class="mainbuttons">
			<?php
				if ($_REQUEST["user_action"] != "new")
				{
					?>
						<a href="javascript: setupDelete(); ajaxSave()" class="deletebutton" title="Delete this item">&nbsp;</a>												
					<?php
				}
				if ($aDisplayWritingStudioButton == true)
				{
					?>
						<a href="<?php print $_SESSION["device"] ?>?type=printView&book_id=<?php print $aBookId ?>&parent_id=<?php print $aBookId ?>&item_id=<?php print $aItemId ?>" class="writingstudiobutton" title="Go to the writing studio for this chapter.">&nbsp;</a>
					<?php
				}
			?>
			<div class="center">
				<a href="javascript: ajaxSave();" class="button">Save</a>
			</div>
		</div>
	<?php
}

function encodeDataArray()
{
	$dataArray 	= array();
	
	foreach($_REQUEST as $key => $value) 
	{
		if (strpos($key, "data_") === 0)
		{
			$dataArray[$key] 	= $value;
		}
	}
	
	$dataString			= serialize($dataArray);
	$escapedDataString	= jEscape($dataString);
	
	return $escapedDataString;
}

function removeLineBreaks($aString)
{
	$returnString	= $aString;
	$returnString 	= str_replace("\r", "", $returnString);
	$returnString 	= str_replace("\n", "", $returnString);
	return $returnString;
}

function lineBreaksToBr($aString)
{
	$returnString	= $aString;
	//$returnString 	= str_replace("\r", "<br/> &nbsp;&nbsp;&nbsp;&nbsp;", $returnString);
	$returnString 	= str_replace(array("\r\n", "\r", "\n"), "<br/> &nbsp;&nbsp;&nbsp;&nbsp;", $returnString);
	return $returnString;
}

function insertParagraphs($aString) 
{
	$returnString	= "";
		
	foreach(preg_split("/(\r?\n)/", $aString) as $line)
	{
		$newParagraph = "<p>" . $line . "</p>";
    	$returnString = $returnString . $newParagraph;    	
	}
		
	return $returnString;
}

?>