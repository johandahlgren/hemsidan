<?php
	if ($_REQUEST["browserWindowWidth"] < 400)
	{
		$fieldWidth = "290";
	}
	else
	{
		$fieldWidth = "500";
	}
?>

<div class="formContainer">
	<div class="formHeader">
		<div class="formHeaderText">Skriv nyhet</div>
		<div class="closeButton" onclick="closeAdmin(); $('.commentButton').css('visibility', 'visible');">X</div>
	</div>
	<div class="formInnerContainer">
		<div class="formLabel">Titel:</div>
		<input type="text" class="formField" name="data_title" value="<?php print getValueFromString("title", $data) ?>" />
		<div class="formLabel">Text:</div>
		<textarea class="formTextArea" id="dataText" name="data_text" style="width: <?php print $fieldWidth ?>px;"><?php print getValueFromString("text", $data) ?></textarea>
		
		<div id="categoriesDiv">
			<?php
				includeForm("category", $data);
			?>
		</div>
	</div>
	<div class="formButtons">
		<div class="center">
			<?php renderSaveButton(); ?>
		</div>
	</div>
</div>
<?php
	if (displayWysiwyg())
	{
		?>
			<script type="text/javascript">
				$(document).ready(function () {
					addWysiwyg("dataText");
				});
			</script>
		<?php
	}
?>