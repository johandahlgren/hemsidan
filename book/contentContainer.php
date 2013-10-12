<?php
	$bookId 	= $_REQUEST["book_id"];
	$parentId 	= $_REQUEST["parent_id"];
	$itemId 	= $_REQUEST["item_id"];
	$type 		= $_REQUEST["type"];
	$userAction = $_REQUEST["user_action"];
	$pageUrl 	= "editContent.php?type=" . $type . "&book_id=" . $bookId  . "&parent_id=" . $parentId . "&item_id=" . $itemId . "&user_action=login&user_id=" . $_SESSION["userId"] . "&password=" . $_SESSION["password"];
?>
<iframe id="mainIFrame" src="<?php print $pageUrl ?>" /> 