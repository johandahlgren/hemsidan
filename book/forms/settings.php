<?php
	$sqlQuery 		= "SELECT user_id, name, words_per_page, skin FROM jb_user WHERE user_id = \"" . $_SESSION["_book_userId"] . "\";";
	$result 		= dbGetSingleRow($sqlQuery);
	$user_id 		= $result[0];
	$name			= $result[1];
	$wordsPerPage	= $result[2];	
	$skin			= $result[3];
?>

<div id="maincontent" class="dropshadow">
	<div class="contentcontainer">
		<div class="block">
			<div class="fieldname">Author Name</div>
			<input type="text" class="inputtextfield" name="author_name" value="<?php print $name ?>" />
		</div>
		<div class="block">
			<div class="fieldname">New Password</div>
			<input type="text" class="inputtextfield" name="new_password" value="" />
		</div>
		<div class="block">
			<div class="fieldname">Words per page</div>
			<textarea class="inputsmalltextarea" name="words_per_page"><?php print $wordsPerPage ?></textarea>						
		</div>				
		<div class="block">			
			<div class="fieldname">Skin</div>			
			<select id="skin_<?php print $itemId ?>" name="skin">				
				<option value="">Select</option>				
				<option value="gray">Gray</option>				
				<option value="green">Green</option>	
				<option value="light">Light</option>
			</select>			
			<script type="text/javascript">				
				document.getElementById("skin_<?php print $itemId ?>").value = "<?php print $skin ?>";			
			</script>		
		</div>
	</div>
	
	<div class="mainbuttons">
		<div class="center">
			<input type="submit" value="Save" class="button" onclick="document.inputForm.elements['user_action'].value = 'update_user';"/>	
		</div>
	</div>
</div>