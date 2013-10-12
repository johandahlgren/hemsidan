<?php
	include_once "../utilityMethods.php";
	
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
?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		
		<script type="text/javascript" src="../resources/jquery-1.4.1.min.js"></script> 
		<script type="text/javascript" src="../javascript/javascript.js"></script>
		<script type="text/javascript" src="../resources/jquery.form.js"></script>
		<link type="text/css" href="../style.css" rel="stylesheet" media="screen" />
		
		<title>The Bookmaker</title>
		
		<script type="text/javascript">
			$(document).ready(function(){
				setupForm();
			});
		</script>
		
	</head>
	<body class="writingstudiobody">
		<div id="ajaxStatusDiv" class="dropshadow">
			<div id="ajaxLoadingAnimation"><img src="images/loadingAnimation.gif" /></div>
			<div id="success" class="statusDiv"></div>
			<div id="failure" class="statusDiv"></div>
		</div>
		<div id="writingStudio">
			<form id="inputForm" name="inputForm" method="post" action="../index.php">
				<fieldset>			
					<input type="hidden" name="user_action" value="save">
					<input type="hidden" name="parent_id" value="<?php print $parentId ?>">
					<input type="hidden" name="book_id" value="<?php print $bookId ?>">
					<input type="hidden" name="item_id" value="<?php print $itemId ?>">
					<input type="hidden" name="type" value="<?php print $type ?>">
					<input type="hidden" name="return_url" value="">
					<input type="hidden" name="connect" value="">
					<input type="hidden" name="connect_to" value="">
					<input type="hidden" name="connection_description" value="">
					<input type="hidden" name="content_id_1" value="">
					<input type="hidden" name="content_id_2" value="">
					<input type="hidden" name="direction" value="">
					<?php
						if ($itemId != null && $itemId != "")
						{
							$sqlQuery 		= "SELECT id, data, sort_order FROM jb_content WHERE id = " . $itemId . ";";
							$result 		= dbGetSingleRow($sqlQuery);
							$id 			= $result[0];
							$data			= $result[1];
							$sortOrder		= $result[2];
						
							$text 			= getValueFromString("text", $data);
							$wordArray 		= explode(" ", $text);
							$numberOfWords 	= count($wordArray);
							?>
								<input type="hidden" name="sort_order" value="<?php print $sortOrder ?>"/>
								<input type="hidden" name="data_status" value="<?php print getValueFromString("status", $data) ?>"/>
								<input type="hidden" name="data_synopsis" value="<?php print getValueFromString("synopsis", $data) ?>"/>
								<input type="hidden" name="data_image" value="<?php print getValueFromString("image", $data) ?>"/>
								<input type="hidden" name="data_image_alt" value="<?php print getValueFromString("image_alt", $data) ?>"/>
								<input type="hidden" name="data_image_alt2" value="<?php print getValueFromString("image_alt2", $data) ?>"/>
								<input type="hidden" name="data_row_style" value="<?php print getValueFromString("row_style", $data) ?>"/>
								
								<div class="block">
									<input type="text" id="title" class="backgroundplate dropshadow" name="data_name" value="<?php print getValueFromString("name", $data) ?>" />							
									<input type="text" class="backgroundplate dropshadow" id="wordCountField" value="<?php print $numberOfWords ?>" />					
								</div>
								<div class="block">
									<textarea class="backgroundplate dropshadow" id="fullText" name="data_text" onkeyup="countWords('fullText', 'wordCountField');"><?php print getValueFromString("text", $data) ?></textarea>				
									<script type="text/javascript">
										/*
										CKEDITOR.replace("fullText",
										{
											height: "460px",
											customConfig : "/johan/book/resources/ckeditor_config.js"
										});
										*/
									</script>
								</div>
								<div class="mainbuttons">
									<div class="center">
										<a href="javascript: $('#editWindow').jqmHide();">Close</a>			
										<input type="submit" value="Save"/>
										<input type="text" id="synonym"/>
										<a href="javascript: displaySynonyms('run');">Synonym</a> 
									</div>
								</div>
							<?php
						}
					?>
				</fieldset>
			</form>
		</div>
	</body>
</html>