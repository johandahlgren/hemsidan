<script type="text/javascript">
	function check(aOldValue, aItemId, aCheckListItemId, aText)
	{
		if (aOldValue == "checked")
		{
			newValue = "";
		}
		else
		{
			newValue = "checked";
		}
		
		document.inputForm.action 								= "<?php print $_SESSION["device"] ?>?"; 
		document.inputForm.elements["type"].value 				= "checklist";
		document.inputForm.elements["data_checked"].value 		= newValue;
		document.inputForm.elements["data_text"].value 			= aText;
		document.inputForm.elements["user_action"].value 		= "check";
		document.inputForm.elements["checklist_item_id"].value 	= aCheckListItemId;
		document.inputForm.submit();
	}
	
	function addChecklist()
	{
		document.inputForm.action = "<?php print $_SESSION["device"] ?>?"; 
		document.inputForm.elements["type"].value = "checklist"; 
		document.inputForm.elements["parent_id"].value = <?php print $bookId ?>; 
		document.inputForm.elements["user_action"].value = "save";
		document.inputForm.elements["item_id"].value = "";
		document.inputForm.submit();
	}
	
	function addChecklistItem()
	{
		document.inputForm.action = "<?php print $_SESSION["device"] ?>?"; 
		document.inputForm.elements["type"].value = "checklist"; 
		document.inputForm.elements["user_action"].value = "save";
		document.inputForm.submit();
	}
	
	function deleteChecklistItem(aChecklistItemId)
	{
		document.inputForm.action = "<?php print $_SESSION["device"] ?>?"; 
		document.inputForm.elements["type"].value = "checklist"; 
		document.inputForm.elements["user_action"].value = "delete";
		document.inputForm.elements["checklist_item_id"].value 	= aChecklistItemId;
		document.inputForm.submit();
	}
</script>

<input type="hidden" name="data_checked" value="" />
<input type="hidden" name="checklist_item_id" value="" />

<div id="maincontent" class="dropshadow">
	<h1><?php print getValueFromString("name", $data) ?></h1>
	<div class="contentcontainer">					
		<?php
			$resultItems = getConnectedContentsByType("checklistItem", $itemId, "right");		
			while (list ($checklistItemId, $itemData) = mysql_fetch_row ($resultItems))
			{
				?>
					<div class="block">
						<div class="checklistCheckBox left" onclick="check('<?php print getValueFromString("checked", $itemData) ?>', <?php print $itemId ?>, <?php print $checklistItemId ?>, '<?php print removeLineBreaks(getValueFromString("text", $itemData)); ?>');">
							<?php 
								if (getValueFromString("checked", $itemData)) 
								{
									?>
										<img src="skins/images/checkmark.png" alt="checked" class="checkmark"/>
									<?php
								}
							?>
						</div>
						<?php print removeLineBreaks(getValueFromString("text", $itemData)) ?>
						<a href="javascript: deleteChecklistItem(<?php print $checklistItemId ?>)" class="blocklink delete">X</a>
					</div>
					<div class="verticalspacer"></div>
				<?php
			}
		?>
		<div class="verticalspacer"></div>
		<div class="block">
			<div class="fieldname">Add new item to this checklist</div>
			<textarea class="fieldwide fieldlow" name="data_text"></textarea>
		</div>
	</div>
	<div class="block mainbuttons">
		<div class="center">
			<a href="javascript: addChecklistItem();" class="button">Add Checklist Item</a>
		</div>
	</div>
</div>