<?php
	ob_start( 'ob_gzhandler' );
	
	include_once "utilityMethods.php";
	include_once "itemMethods.php";
	
	$_SESSION["device"] = "index.php";
	
	if ($_REQUEST["book_id"] != null && $_REQUEST["book_id"] != "")
	{
		$_SESSION["bookId"] = $_REQUEST["book_id"];
	}
	if ($_REQUEST["parent_id"] != null && $_REQUEST["parent_id"] != "")
	{
		$_SESSION["parentId"] = $_REQUEST["parent_id"];
	}
	/*
	if ($_REQUEST["item_id"] != null && $_REQUEST["item_id"] != "")
	{
		$_SESSION["itemId"] = $_REQUEST["item_id"];
	}
	*/
	if ($_REQUEST["item_id"] != null && $_REQUEST["item_id"] != "")
	{
		$itemId = $_REQUEST["item_id"];
	}
	if ($_REQUEST["type"] != null && $_REQUEST["type"] != "")
	{
		$_SESSION["type"] = $_REQUEST["type"];
	}

	$bookId 	= $_SESSION["bookId"];
	$parentId 	= $_SESSION["parentId"];
	//$itemId 	= $_SESSION["itemId"];
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
					
					<script type="text/javascript" src="resources/jquery-1.6.1.min.js"></script> 
					<script type="text/javascript" src="javascript.js"></script>
					<script type="text/javascript" src="resources/piroBox.1_2_min.js"></script>
					<script type="text/javascript" src="resources/jquery.form.js"></script>
					<script type="text/javascript" src="resources/jqModal.js"></script>
					<link href="css/css_pirobox/white/style.css" media="screen" title="white" rel="stylesheet" type="text/css" />
					<link rel="shortcut icon" href="skins/images/favicon.png" type="image/png">
					<link type="text/css" href="skins/generic.css" rel="stylesheet" media="screen" />
					<link type="text/css" href="skins/<?php print $skinName ?>/style.css" rel="stylesheet" media="screen" />
					
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
									if (handleLogin("normal"))
									{
										if ($_SESSION["_book_userId"] != null && $_SESSION["_book_userId"] != "")
										{
											?>
												<div id="head">
													<div id="top">
														<div id="mainMenu">
															<div class="logo"></div>
															<?php
																if ($_SESSION["bookId"] != null && $_SESSION["bookId"] != "")
																{
																	?>
																		<div class="booktitle"><?php print $bookName ?></div>
																		<div class="bookauthor">by: <?php print $_SESSION["_book_userName"] ?></div>
																		<div id="messageDiv">
																			<div id="ajaxLoadingAnimation"><img src="skins/images/loadingAnimation.gif" /></div>
																			<div id="statusMarker"></div>
																		</div>
																	<?php
																}
															?>
															<a href="index.php?user_action=logout" class="button right mainmenubutton">Log out</a>
															<a href="index.php?type=settings" class="button right mainmenubutton">Settings</a>
															<div class="loggedininfo">Logged in as: <?php print $_SESSION["_book_userId"] ?></div>
														</div>
														<div id="tabsContainer">
															<a class="tab <?php if ($type == "book") print "selected" ?>" href="index.php?type=book&book_id=<?php print $bookId ?>"><span>Books</span></a>
															<?php
																if ($bookId != null && $bookId != "")
																{
																	?>
																		<a class="tab <?php if ($type == "chapter") print "selected" ?>" href="index.php?type=chapter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Chapters</span></a>
																		<a class="tab <?php if ($type == "majorCharacter") print "selected" ?>" href="index.php?type=majorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Major Characters</span></a>
																		<a class="tab <?php if ($type == "minorCharacter") print "selected" ?>" href="index.php?type=minorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Minor Characters</span></a>
																		<a class="tab <?php if ($type == "location") print "selected" ?>" href="index.php?type=location&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Locations</span></a>
																		<a class="tab <?php if ($type == "item") print "selected" ?>" href="index.php?type=item&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Items</span></a>
																		<a class="tab <?php if ($type == "world") print "selected" ?>" href="index.php?type=world&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>The World</span></a>
																		<div class="extramenubuttons">
																			<a class="tab" href="read.php?type=read&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>&item_id=<?php print $itemId ?>" onclick="this.target='_blank';"><span>Read</span></a>
																			<a class="tab" href="import.php?book_id=<?php print $bookId ?>" onclick="this.target='_blank';"><span>Import</span></a>
																			<a class="tab" href="export.php?book_id=<?php print $bookId ?>" onclick="this.target='_blank';"><span>Export</span></a>
																			<!--
																			<a class="tab <?php if ($type == "printView") print "selected" ?>" href="index.php?type=printView&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Writing Studio</span></a>																	
																			<a class="tab <?php if ($type == "timeline") print "selected" ?>" href="index.php?type=timeline&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Time Line</span></a>
																			-->
																			<a class="tab <?php if ($type == "checklist") print "selected" ?>" href="index.php?type=checklist&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>"><span>Checklists</span></a>
																		</div>
																	<?php
																}
															?>
														</div>
													</div>
												</div>
												<?php
													if ($bookId != null && $bookId != "")
													{
														?>
															<div id="bookInfo">
																<?php
																	
																	$sqlQuery2 	= "SELECT id, data, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $bookId . " AND c.type = \"chapter\" ORDER BY c.sort_order, c.id;";
																	$result2 	= dbGetMultipleRows($sqlQuery2);
																	$fullText	= "";
																	
																	while (list ($id2, $data2, $sortOrder2) = mysql_fetch_row($result2))
																	{
																		$text 		= getValueFromString("text", $data2);
																		$fullText	= $fullText . $text;
																	}
																	
																	$wordArray 			= explode(" ", $fullText);
																	$numberOfWords 		= count($wordArray);
																	$numberOfCharacters = strlen($fullText);
																	
																	$sqlQuery 		= "SELECT words_per_page FROM jb_user WHERE user_id = \"" . $_SESSION["_book_userId"] . "\";";
																	$result 		= dbGetSingleRow($sqlQuery);
																	$wordsPerPage 	= $result[0];
																	if ($wordsPerPage == 0)
																	{
																		$wordsPerPage = 300;
																	}
																	
																	$numberOfPages = round($numberOfWords / $wordsPerPage, 0);
																?>
																 <span class="bold">Book statistics:</span> <?php print $numberOfPages ?> pages (at <?php print number_format($wordsPerPage, 0) ?> words per page), <?php print number_format($numberOfWords, 0) ?> words, <?php print number_format($numberOfCharacters, 0) ?> characters
															</div>
														<?php
													}
												?>
											<?php
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
											?>
												<input type="hidden" id="userAction" name="user_action" value="<?php print $action; ?>">
												
												<div id="mainDiv">
													<?php 
														include("forms/__common.php");
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