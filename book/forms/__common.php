<?php
	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		$sqlQuery 	= "SELECT id, name, sort_order, data FROM jb_content WHERE id = " . $itemId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		$id 		= $result[0];
		$name		= $result[1];
		$sortOrder	= $result[2];
		$data		= $result[3];		
	}
	
	$name 			= getValueFromString("name", $data);
	
	if ($itemId == null || $itemId == "")
	{
		$itemId = 0;
	}
	
	if ($userAction == "new" || ($itemId != null && $itemId != ""))
	{
		?>
			<div id="leftMenu">
				<?php 
					if ($type == "book")
					{
						renderBookList($_SESSION["_book_userId"], "book");
					}
					else if ($type == "printView")
					{
						renderPrintList($_SESSION["bookId"], $_SESSION["bookId"]);
					}
					else
					{
						if ($type == "read")
						{
							$modifiedType = "chapter";
						}
						else
						{
							$modifiedType = $type;
						}
						renderList("menu", "", $modifiedType, $bookId, $bookId, "leftMenuDiv", false, true, true);
					}
				?>
			</div>

			<input type="hidden" name="sort_order" value="<?php print $sortOrder ?>" />
			<input type="hidden" name="content_name" value="<?php print $name ?>">
			
			<?php
				include("_" . $type . ".php");
				include($type . ".php");
			?>
		<?php
	}
	else
	{
		if ($type == "settings")
		{
			include("settings.php");
		}
		else if ($type == "book")
		{
			renderBookList($_SESSION["_book_userId"], "book");
		}
		else if ($type == "printView")
		{
			renderPrintList($_SESSION["bookId"], $_SESSION["bookId"]);
		}
		else if ($type == "chapter" || $type == "read" || $type == "checklist")
		{
			if ($type == "read")
			{
				$type = "chapter";
			}
			renderList("menu", "", $type, $bookId, $bookId, "leftMenuDiv", false, false, false);
		}
		else
		{
			renderOverview($type, $bookId, $bookId);
		}
	}
?>