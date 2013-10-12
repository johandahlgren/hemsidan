<?php
	if ($_REQUEST["user_action"] == "save")
	{
		if ($_REQUEST["sub_type"] == "checklist")
		{
			createNewItem($bookId, $parentId, $_REQUEST["sub_type"], $aBookUserId);
		}
		else if($_REQUEST["sub_type"] == "checklistItem")
		{
			createNewItem($bookId, $itemId, $_REQUEST["sub_type"], $aBookUserId);
		}
	}
	else if ($_REQUEST["user_action"] == "check")
	{
		saveItem($_REQUEST["checklist_item_id"]);
	}
	else if ($_REQUEST["user_action"] == "delete")
	{
		deleteItem($_REQUEST["checklist_item_id"]);
	}

	if ($itemId != null && $itemId != "" && $_REQUEST["user_action"] != "delete")
	{
		$sqlQuery 	= "SELECT id, data FROM jb_content WHERE id = " . $itemId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		$id 		= $result[0];
		$data		= $result[1];		
	}

	if ($userAction == "new")
	{
		?>
			<div id="maincontent" class="dropshadow">
				<input type="hidden" name="sub_type" value="checklist" />
				<h1>New checklist</h1>
				<div class="contentcontainer">
					<div class="block">
						<div class="fieldname">Name</div>
						<input type="text" class="fieldwide" name="data_name" value="<?php print getValueFromString("name", $data) ?>" />
					</div>
				</div>
				<div class="block mainbuttons">
					<div class="center">
						<a href="javascript: addChecklist();" class="button">Save</a>
					</div>
				</div>
			</div>
		<?php
	}
	else if ($itemId != null && $itemId != "")
	{
		?>
			<input type="hidden" name="sub_type" value="checklistItem" />
		<?php
	}
	else
	{
		printInfoMessage("Select the checklist you want to work with in the menu on the left.");		
	}
?>