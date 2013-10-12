<div id="maincontent" class="dropshadow">
	<div class="contentcontainer">
		<div class="block">
			<div class="fieldname">Name</div>
			<input type="text" class="fieldwide" name="data_name" value="<?php print getValueFromString("name", $data) ?>" />
		</div>		
		<div class="block">
			<div class="fieldname">Synopsis</div>
			<textarea class="fieldwide fieldlow" name="data_synopsis"><?php print getValueFromString("synopsis", $data) ?></textarea>	
		</div>
		<div class="block">
			<div class="fieldname">Text</div>
		</div>
		<div class="block">
			<textarea class="fieldwide fieldhigh" id="fullText" name="data_text"><?php print strip_tags(getValueFromString("text", $data)) ?></textarea>
		</div>
		<div class="block">
			<?php
				$wordArray 			= explode(" ", getValueFromString("text", $data));
				$numberOfWords 		= count($wordArray);
			?>
			<div class="fieldname">Words: </div>
				<?php 
					print $numberOfWords 
				?> 
				or roughly 
				<?php
					$sqlQuery 		= "SELECT words_per_page FROM jb_user WHERE user_id = \"" . $_SESSION["_book_userId"] . "\";";
					$result 		= dbGetSingleRow($sqlQuery);
					$wordsPerPage 	= $result[0];
					if ($wordsPerPage == 0)
					{
						$wordsPerPage = 300;
					}
					print round($numberOfWords / $wordsPerPage, 1);
				?> 
				paperback pages (at <?php print $wordsPerPage ?> words per page).
		</div>
		<div class="block">
			<div class="left">
				<div class="fieldname">Status</div>
				<select id="status" name="data_status">
					<option value="1">Not started</option>
					<option value="2">Working</option>
					<option value="3">Draft</option>
					<option value="4">Finished</option>
				</select>
				<script type="text/javascript">
					document.getElementById("status").value = "<?php print getValueFromString("status", $data) ?>";
				</script>
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
				<div class="fieldname">Chron. Order</div>
				<input type="text" class="inputtextfieldtiny" name="data_chronological_order" value="<?php print getValueFromString("chronological_order", $data) ?>" />
			</div>
		</div>
		<div class="block">
			<div class="left">
				<div class="fieldname">Background image</div>
				<input type="text" class="inputtextfield" name="data_background_image" value="<?php print getValueFromString("background_image", $data) ?>" />
			</div>
			<div class="left">
				<div class="fieldname">Public</div>
				<select id="is_public_<?php print $itemId ?>" name="data_is_public">
					<option value="false">No</option>
					<option value="true">Yes</option>
				</select>
				<script type="text/javascript">
					document.getElementById("row_style_<?php print $itemId ?>").value = "<?php print getValueFromString("row_style", $data) ?>";
					document.getElementById("is_public_<?php print $itemId ?>").value = "<?php print getValueFromString("is_public", $data) ?>";
				</script>
			</div>
		</div>
	</div>
	<!--
	<div class="block mainbuttons">
		<div class="center">
			<a href="index.php?type=printView&book_id=<?php print $bookId ?>&parent_id=<?php print $parentId ?>&item_id=<?php print $id ?>" class="blocklink">Writing Studio</a>
			<a href="javascript: deleteItem(<?php print $itemId ?>, '<?php print $currentUrl ?>')" class="deletebutton">Delete</a>								
			<input type="submit" value="Save"/>
		</div>
	</div>
	-->
	<?php
		renderButtons("Chapters", "chapter", $itemId, $bookId, $currentUrl, true);
	?>
</div>