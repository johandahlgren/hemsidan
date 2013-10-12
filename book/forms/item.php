<?php
	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		?>
			<div id="rightcontent">
				<?php
					renderListAndConnection("list", "Appears in chapter", "chapter", $itemId, $bookId, "appearsInDiv", true);
					renderListAndConnection("list", "Used by", "majorCharacter,minorCharacter", $itemId, $bookId, "majorCharacterDiv", true);
				?>			
			</div>
		<?php
	}
?>