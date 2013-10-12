<?php
	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		?>
			<div id="rightcontent">
				<?php
					renderListAndConnection("list", "Appears in chapter", "chapter", $itemId, $bookId, "appearsInDiv", true);
				?>
			</div>
		<?php
	}
?>