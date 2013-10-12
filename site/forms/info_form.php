<span class="formlabel">Titel:</span>
<input type="text" class="formfield" name="data_title" value="<?php print getValueFromString("title", $data) ?>" />
<span class="formlabel">Bild:</span>
<input type="text" class="formfield" name="data_image" value="<?php print getValueFromString("image", $data) ?>" />
<div class="formLabel">Text:</div>
<textarea class="" id="dataText" name="data_text" style="width: 690px;"><?php print getValueFromString("text", $data) ?></textarea>

<div class="formButtons">
	<div class="center">
		<?php renderCloseButton(); ?>
		<?php renderSaveButton(); ?>
	</div>
</div>