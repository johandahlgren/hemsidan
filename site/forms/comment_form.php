<div class="formContainer">
	<div class="formHeader">
		<div class="formHeaderText">Skriv kommentar</div>
		<div class="closeButton" onclick="closeAdmin(); $('.commentButton').css('visibility', 'visible');">X</div>
	</div>
	<div class="formInnerContainer">
		<input type="hidden" id="lock1" name="lock1" value=""/>
		<input type="hidden" id="lock2" name="lock2" value=""/>
		<div class="formLabel">Namn:</div>
		<input type="text" name="data_visitorName" class="commentsField" value="<?php print $currentDahlgrenUser ?>"/>
		<div class="formLabel">Kommentar:</div>
		<textarea name="data_commentText" class="commentsTextArea"></textarea>
		<div class="center">
			<div id="saveButton" class="adminButton" onclick="ajaxSave(); $('.commentButton').css('visibility', 'visible')">
				Spara
			</div>
		</div>
	</div>
</div>