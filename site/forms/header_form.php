<div class="formContainer">
	<div class="formHeader">
		<div class="formHeaderText">&Auml;ndra sidhuvudet</div>
		<div class="closeButton" onclick="closeAdmin(); $('.commentButton').css('visibility', 'visible');">X</div>
	</div>
	<div class="formInnerContainer">
		<div class="formLabel">Titel:</div>
		<input type="text" class="formField" name="data_title" value="<?php print getValueFromString("title", $data) ?>" />
		<div class="formLabel">Undertitel:</div>
		<input type="text" class="formField" name="data_subTitle" value="<?php print getValueFromString("subTitle", $data) ?>" />
		<div class="formLabel">Bild:</div>
		<input type="text" class="formField" name="data_image" value="<?php print getValueFromString("image", $data) ?>" />
		<div class="formLabel">Position:</div>
		<input type="text" class="formField" name="data_backgroundPosition" value="<?php print getValueFromString("backgroundPosition", $data) ?>" />
	</div>
	<div class="formButtons">
		<div class="center">
			<?php renderSaveButton(); ?>
		</div>
	</div>
</div>