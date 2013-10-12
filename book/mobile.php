<?php
	ob_start( 'ob_gzhandler' );
	
	include_once "utilityMethods.php";
	include_once "itemMethods.php";
	
	$_SESSION["device"] = "mobile.php";
	
	if ($_REQUEST["book_id"] != null && $_REQUEST["book_id"] != "")
	{
		$_SESSION["bookId"] = $_REQUEST["book_id"];
	}
	if ($_REQUEST["parent_id"] != null && $_REQUEST["parent_id"] != "")
	{
		$_SESSION["parentId"] = $_REQUEST["parent_id"];
	}
	if ($_REQUEST["item_id"] != null && $_REQUEST["item_id"] != "")
	{
		$_SESSION["itemId"] = $_REQUEST["item_id"];
	}
	if ($_REQUEST["type"] != null && $_REQUEST["type"] != "")
	{
		$_SESSION["type"] = $_REQUEST["type"];
	}

	$bookId 	= $_SESSION["bookId"];
	$parentId 	= $_SESSION["parentId"];
	$itemId 	= $_SESSION["itemId"];
	$type 		= $_SESSION["type"];
	
	$currentUrl	= $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

	if ($_REQUEST["return_url"] != null && $_REQUEST["return_url"] != "" && !$errorHasOccured)
	{
		?>
			<meta http-equiv="refresh" content="0;url=<?php print $_REQUEST["return_url"] ?>">
			<!--<a href="<?php print $_REQUEST["return_url"] ?>">Continue</a>-->
		<?php
	}
	else
	{
		if ($_SESSION["bookId"] != null && $_SESSION["bookId"] != "" && $_REQUEST["user_action"] != "new")
		{
			$sqlQuery 	= "SELECT id, data FROM jb_content WHERE id = " . $bookId . ";";
			$result 	= dbGetSingleRow($sqlQuery);
			$id 		= $result[0];
			$data		= $result[1];
			
			$bookName	= getValueFromString("name", $data);
		}
		
		$skinName = $_SESSION["_book_skin"];
		if ($skinName == null || $skinName == "")
		{
			$skinName = "light";
			$_SESSION["_book_skin"] = $skinName;
		}
		
		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
					<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
					<meta name="apple-mobile-web-app-capable" content="yes" />
					<meta name="apple-mobile-web-app-status-bar-style" content="black" />
					
					<script type="text/javascript" src="resources/jquery-1.4.1.min.js"></script> 
					<script type="text/javascript" src="javascript.js"></script>
					<script type="text/javascript" src="resources/piroBox.1_2_min.js"></script>
					<script type="text/javascript" src="resources/jquery.form.js"></script>
					<script type="text/javascript" src="resources/jqModal.js"></script>
					<link href="css/css_pirobox/white/style.css" media="screen" title="white" rel="stylesheet" type="text/css" />
					<link rel="shortcut icon" href="skins/images/favicon.png" type="image/png">
					<link type="text/css" href="skins/generic.css" rel="stylesheet" media="screen" />
					<link type="text/css" href="skins/<?php print $skinName ?>/mobile.css" rel="stylesheet" media="screen" />
					
					<title>The Bookmaker</title>
					
					<script type="text/javascript"> 
						$(document).ready(function(){
							$().piroBox({
								my_speed: 300, //animation speed
								bg_alpha: 0.75, //background opacity
								radius: 4, //caption rounded corner
								scrollImage : false, // true == image follows the page _|_ false == image remains in the same open position
										   // in some cases of very large images or long description could be useful.
								slideShow : 'true', // true == slideshow on, false == slideshow off
								slideSpeed : 3, //slideshow 
								pirobox_next : 'piro_next', // Nav buttons -> piro_next == inside piroBox , piro_next_out == outside piroBox
								pirobox_prev : 'piro_prev', // Nav buttons -> piro_prev == inside piroBox , piro_prev_out == outside piroBox
								close_all : '.piro_close,.piro_overlay' // add class .piro_overlay(with comma)if you want overlay click close piroBox
								});
							
							<?php
								if ($_SESSION["_book_userIsLoggedIn"] 	== true)
								{
									?>
										setupForm();
									<?php
								}
							?>
						});
					</script>
					
				</head>
				<body>
					<div id="messageDiv" style="display: none;">
						<div id="ajaxLoadingAnimation"></div>
						<div id="statusMarker"></div>
					</div>
					<div id="container">
						<form id="inputForm" name="inputForm" method="post" action="ajaxService.php">
							<fieldset>							
								<input type="hidden" name="parent_id" value="<?php print $parentId ?>">
								<input type="hidden" name="book_id" value="<?php print $bookId ?>">
								<input type="hidden" name="item_id" value="<?php print $itemId ?>">
								<input type="hidden" name="type" value="<?php print $type ?>">
								<input type="hidden" name="title" value="<?php print $title ?>">
								<input type="hidden" name="return_url" value="">
								<input type="hidden" name="connect" value="">
								<input type="hidden" name="connect_to" value="">
								<input type="hidden" name="connection_description" value="">
								<input type="hidden" name="content_id_1" value="">
								<input type="hidden" name="content_id_2" value="">
								<input type="hidden" name="direction" value="">
								<input type="hidden" name="list_type" value="">
								
								<?php
									if (handleLogin("mobile"))
									{
										if ($_SESSION["_book_userId"] != null && $_SESSION["_book_userId"] != "")
										{
											
										}
										
											
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
												<div id="infoDivError" class="dropshadow">
													You do not have access to this book!
												</div>
											<?php
										}
										else if (!$itemBelongsToBook)
										{
											?>
												<div id="infoDivError" class="dropshadow">
													This item does not belong to the selected book.<br/>
													Are you trying to spy on your fellow authors?<br/>
													<br/>
													Naughty, naughty!
												</div>
											<?php
										}
										else
										{
											if ($type == null || $type == "")
											{
												$type = "book";
											}
											
											if ($_REQUEST["user_action"] == "new")
											{
												$action = "new";
											}
											else
											{
												$action = "save";
											}
											
											if ($type == "selection")
											{
												$backAddress = $_SESSION["device"];
											}											
											else if ($itemId == null)
											{
												$backAddress = $_SESSION["device"] . "?type=selection";
											}
											else
											{
												$backAddress = $_SESSION["device"] . "?type=" . $type;
											}
												
											?>
												<input type="hidden" id="userAction" name="user_action" value="<?php print $action; ?>">
												
												<div class="header"> 
													<?php 
														if ($type != "book")
														{
															?>
																<a href="<?php print $backAddress ?>" class="button">&#8592;</a>
															<?php
														}
													?>
													
													<div class="headertext">
														<?php 
															if ($type == "book") print "Books";
															else if ($type == "selection") print "Selection";
															else if ($type == "read") print "Read chapter";
															else if ($type == "chapter") print "Chapters";
															else if ($type == "majorCharacter") print "Major Characters";
															else if ($type == "minorCharacter") print "Minor Characters";
															else if ($type == "location") print "Locations";
															else if ($type == "item") print "Items";
															else if ($type == "world") print "World Items";
															else if ($type == "checklist") print "Checklists";
														?>
													</div>
												</div>
												
												<div id="mobileContainer">
													<?php
														if ($type == "book")
														{
															renderBookList($_SESSION["_book_userId"], "selection");
														}
														else if ($type == "selection")
														{
															?>
																<table class="listtable">
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=read&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Read</a>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=chapter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Chapters</a>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=majorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Major Characters</a>
																			</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=minorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Minor Characters</a>
																			</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=location&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Locations</a>
																			</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=item&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Items</a>
																			</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=world&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">The World</a>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<a href="<?php print $_SESSION["device"] ?>?type=checklist&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>" class="blocklink">Checklists</a>
																		</td>
																	</tr>
																</table>
															<?php
														}
														else
														{
															if ($itemId == null || $itemId == $parentId)
															{
																if ($type != "read")
																{
																	$listType = "menu";
																}
																renderList($listType, "", $type, $bookId, $bookId, "leftMenuDiv", false);
															}
															else
															{
																if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
																{
																	$sqlQuery 	= "SELECT id, sort_order, data FROM jb_content WHERE id = " . $itemId . ";";
																	$result 	= dbGetSingleRow($sqlQuery);
																	$id 		= $result[0];
																	$sortOrder	= $result[1];
																	$data		= $result[2];		
																}
																
																if ($itemId == null || $itemId == "")
																{
																	$itemId = 0;
																}
															
																include("forms/_" . $type . ".php");
															}
														}
													?>
												</div>
											<?php
										}
									}
								?>
							</fieldset>
						</form>
					</div>
				</body>
			</html>
		<?php
	}
	ob_end_flush();
?>