<?php
	if ($itemId != null && $itemId != "")
	{
		?>
			<div id="rightcontent">
				<?php
					renderListAndConnection("list", "Locations", "location", $itemId, $bookId, "locationDiv", true);
					renderListAndConnection("list", "Major characters", "majorCharacter", $itemId, $bookId, "majorCharacterDiv", true);
					renderListAndConnection("list", "Minor characters", "minorCharacter", $itemId, $bookId, "minorCharacteriv", true);
					renderListAndConnection("list", "Items", "item", $itemId, $bookId, "itemDiv", true);
					renderListAndConnection("list", "World items", "world", $itemId, $bookId, "worldDiv", true);
				?>
			</div>
		<?php 
	}

	if ($_REQUEST["writing_studio"] != null)
	{
		?>
			</div>
		<?php
	}
?>
