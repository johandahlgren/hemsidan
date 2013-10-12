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
				<div class="fieldname">Category</div>
				<input type="text" class="fieldnarrow" name="data_category" value="<?php print getValueFromString("category", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">Description</div>
				<textarea class="fieldnarrow fieldlow" name="data_background"><?php print getValueFromString("background", $data) ?></textarea>						
			</div>
			<div class="block">
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
			<div class="block">
				<div class="left">
					<div class="fieldname">Latitude</div>
					<input type="text" class="inputtextfieldsmall" name="data_latitude" value="<?php print getValueFromString("latitude", $data) ?>" />					
				</div>
				<div class="left">
					<div class="fieldname">Longitude</div>
					<input type="text" class="inputtextfieldsmall" name="data_longitude" value="<?php print getValueFromString("longitude", $data) ?>" />						
				</div>
				<div class="left">
					<div class="fieldname">Zoom</div>
					<input type="text" class="inputtextfieldsmall" name="data_zoom" value="<?php print getValueFromString("zoom", $data) ?>" />					
				</div>
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
		<div class="block">
			<?php 
				if (getValueFromString("latitude", $data) != null && getValueFromString("longitude", $data) != null)
				{
					?>
					<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAkcDyp15sYatw4QqEWOnlCBSWytRl57EGpO9A-gkm8hP7HLu_1hTCIZZdIxjSt_23aCjoVnBrtqnrtQ&sensor=false" type="text/javascript"></script>

					<div class="fieldname">Map</div>
					<div id="locationMap"></div></div> 
					
					<script type="text/javascript"> 
						map 			= initializeMap(new GLatLng(<?php print getValueFromString("latitude", $data) ?>, <?php print getValueFromString("longitude", $data) ?>), <?php print getValueFromString("zoom", $data) ?>);
						var oldPoint 	= null;
						<?php
							$resultLocations = getConnectedContentsByType("location", $parentId, "right");
							while (list ($id, $locationData) = mysql_fetch_row ($resultLocations))
							{
								if (getValueFromString("latitude", $locationData) != null && getValueFromString("longitude", $locationData) != null)
								{
									$href = "index.php?type=location&book_id=" . $bookId . "&parent_id=" . $parentId . "&item_id=" . $id;
									?>
										var newPoint = new GLatLng(<?php print getValueFromString("latitude", $locationData) ?>, <?php print getValueFromString("longitude", $locationData) ?>);
										setMapMarker(map, newPoint, "<?php print getValueFromString("name", $locationData) ?>", "<?php print $href ?>");
									<?php
								}
							}
							$sqlQueryChapters 	= "SELECT id, data FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $parentId . " AND c.type = \"chapter\" ORDER BY c.sort_order;";
							$resultChapters 	= dbGetMultipleRows($sqlQueryChapters);

							while (list ($chapterId, $chapterData) = mysql_fetch_row ($resultChapters))
							{
								$sqlQueryLocations 	= "SELECT id, data FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $chapterId . " AND c.type = \"location\" ORDER BY c.id;";
								$resultLocations 	= dbGetMultipleRows($sqlQueryLocations);

								while (list ($locationId, $locationData) = mysql_fetch_row ($resultLocations))
								{
									if (getValueFromString("latitude", $locationData) != null && getValueFromString("longitude", $locationData) != null)
									{
										?>
											var newPoint = new GLatLng(<?php print getValueFromString("latitude", $locationData) ?>, <?php print getValueFromString("longitude", $locationData) ?>);
											
											if (oldPoint != null)
											{
												drawLine(map, oldPoint, newPoint);
											}
											oldPoint = newPoint;
										<?php
									}
								}
							}
						?>
					</script>	
					<?php
				}
			?>
		</div>
	</div>
	<?php
		renderButtons("Locations", "location", $bookId, $bookId, $currentUrl, false);
	?>
</div>