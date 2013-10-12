<?php
	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		?>
			<div id="rightcontent">
				<?php
					renderListAndConnection("list", "Appears in chapter", "chapter", $itemId, $bookId, "appearsInDiv", true);
					renderListAndConnection("list", "Relations", "majorCharacter,minorCharacter", $itemId, $bookId, "majorCharacterDiv", false);
					renderListAndConnection("list", "Uses items", "item", $itemId, $bookId, "usesItemDiv", true);
					renderListAndConnection("list", "Frequents", "location,minorCharacter", $itemId, $bookId, "majorCharacterDiv", true);
				?>
			</div>
		<?php
	}
?>