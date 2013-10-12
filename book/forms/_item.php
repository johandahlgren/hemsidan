<div id="maincontent" class="dropshadow">
	<div class="contentcontainer">
		<div class="left66percent">
			<div class="block">
				<div class="fieldname">Name</div>
				<input type="text" class="fieldnarrow" name="data_name" value="<?php print getValueFromString("name", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">Short name</div>
				<input type="text" class="fieldnarrow" name="data_short_name" value="<?php print getValueFromString("short_name", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">A.K.A.</div>
				<input type="text" class="fieldnarrow" name="data_aka" value="<?php print getValueFromString("aka", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">Category</div>
				<input type="text" class="fieldnarrow" name="data_category" value="<?php print getValueFromString("category", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">Description</div>
				<textarea class="fieldnarrow fieldlow" name="data_description"><?php print getValueFromString("description", $data) ?></textarea>						
			</div>
			<div class="block">
				<div class="fieldname">Appearance</div>
				<textarea class="fieldnarrow fieldlow" name="data_appearance"><?php print getValueFromString("appearance", $data) ?></textarea>						
			</div>
			<div class="left">
				<div class="fieldname">Background</div>
				<select id="row_style_<?php print $itemId ?>" name="data_row_style">
					<option value="">Default</option>
					<option value="rowgray">Gray</option>
					<option value="rowred">Pink</option>
					<option value="rowgreen">Green</option>
					<option value="rowblue">Blue</option>
				</select>
				<script type="text/javascript">
					document.getElementById("row_style_<?php print $itemId ?>").value = "<?php print getValueFromString("row_style", $data) ?>";
				</script>
			</div>
			<div class="left">
				<div class="fieldname">Type</div>
				<select id="type_<?php print $itemId ?>" name="data_type">
					<option value="">Normal</option>
					<option value="category">Category</option>
				</select>
				<script type="text/javascript">
					document.getElementById("type_<?php print $itemId ?>").value = "<?php print getValueFromString("type", $data) ?>";
				</script>
			</div>
		</div>
		<div class="right">
			<div class="fieldname">Image</div>
			<?php 
				printImage("image", getValueFromString("image", $data), getValueFromString("name", $data));
				printImage("image_alt", getValueFromString("image_alt", $data), getValueFromString("name", $data));
				printImage("image_alt2", getValueFromString("image_alt2", $data), getValueFromString("name", $data));							
			?>	
		</div>
	</div>
	<?php
		renderButtons("Items", "item", $bookId, $bookId, $currentUrl, false);
	?>
</div>