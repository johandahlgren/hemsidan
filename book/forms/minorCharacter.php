<?php
	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		?>
			<div id="rightcontent">
				<?php
					renderListAndConnection("list", "Appears in chapter", "chapter", $itemId, $bookId, "appearsInDiv", true);
					renderListAndConnection("list", "Relations", "majorCharacter,minorCharacter", $itemId, $bookId, "relationsDiv", false);
					renderListAndConnection("list", "Uses items", "item", $itemId, $bookId, "usesItemDiv", true);
					renderListAndConnection("list", "Frequents", "location", $itemId, $bookId, "frequentsDiv", true);
				?>
			</div>
		<?php
	}
?>