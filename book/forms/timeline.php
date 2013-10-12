<input type="hidden" name="data_sort_order">
<input type="hidden" name="data_row_span" value="1"/>

<div class="center">
	<table class="timelinetable">
		<?php
			$characterArray 	= array();
			$resultCharacters 	= getConnectedContentsByType("majorCharacter", $bookId, "right");		
			$i 					= 0;
			$longestArrayLength = 0;
			
			while (list ($characterId, $characterData) = mysql_fetch_row ($resultCharacters))
			{
				$counter 					= 0;
				if (getValueFromString("short_name", $characterData) != "")
				{
					$name 						= getValueFromString("short_name", $characterData);
				}
				else
				{
					$name 						= getValueFromString("name", $characterData);
				}
				$characterTimeLine			= array();
				$characterTimeLine["id"] 	= $characterId;
				$characterTimeLine["name"] 	= $name;
				$resultItems 				= getConnectedContentsByType("timelineItem", $characterId, "right");
				
				while (list ($timelineItemId, $timelineItemData, $sortOrder) = mysql_fetch_row ($resultItems))
				{
					$itemArray 						= array();
					$itemArray["id"]				= $timelineItemId;
					$itemArray["text"]				= getValueFromString("text", $timelineItemData);
					$itemArray["rowSpan"]			= getValueFromString("row_span", $timelineItemData);
					$itemArray["sortOrder"]			= $sortOrder;
					$characterTimeLine["sortOrder"]	= $sortOrder;
					
					$characterTimeLine[$counter] 	= $itemArray;
					$counter 						= $counter + 1;
					
					if ($counter > $longestArrayLength)
					{
						$longestArrayLength 		= $counter + 1;
					}
				}
				$characterArray[$i] = $characterTimeLine;
				$i					= $i + 1;
			}
			
			
			$resultChapter 	= getConnectedContentsByType("timelineChapter", $bookId, "right");
			$chapterArray	= array();
			$i				= 0;
			
			while (list ($timelineChapterId, $timelineChapterData, $chapterSortOrder) = mysql_fetch_row ($resultChapter))
			{
				$chapterData				= array();
				$chapterData["id"]			= $timelineChapterId;
				$chapterData["text"]		= getValueFromString("text", $timelineChapterData);
				$chapterData["sortOrder"]	= $chapterSortOrder;
				$chapterArray[$i]			= $chapterData;
				$i = $i + 1;
			}
			
			?>
				<tr>
					<td>
					</td>
					<?php
						for ($y = 0; $y < count($characterArray); $y = $y + 1)
						{	
							?>
								<th><a href="index.php?type=majorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $bookId ?>&item_id=<?php print $characterArray[$y]["id"] ?>"><?php print $characterArray[$y]["name"]; ?></th>
							<?php
						}
					?>
				</tr>
			<?php

			$oldChapterName 		= "";
			$currentChapterName		= $chapterArray[0];
			
			for ($x = 0; $x < $longestArrayLength; $x = $x + 1)
			{
				$rowClass			= "";
				$chapterId	 		= $chapterArray[$x]["id"];
				$chapterName 		= $chapterArray[$x]["text"];
				$chapterSortOrder	= $chapterArray[$x]["sortOrder"];
				$currentChapterName	= "";
				
				if ($chapterName != $oldChapterName)
				{
					$currentChapterName = $chapterName;
					$oldChapterName		= $chapterName;
					$rowClass			= "chapterdivider";
				}
				?>
					<tr class="<?php print $rowClass ?>">
						<td>
							<a href="javascript: editTimelineItem(<?php print $chapterId ?>, '<?php print $chapterName ?>', <?php print $chapterSortOrder ?>, 1);">
								<?php print $currentChapterName ?>
							</a>
						</td>
						<?php
							for ($y = 0; $y < count($characterArray); $y = $y + 1)
							{	
								$rowSpan = $characterArray[$y][$x]["rowSpan"];
								if ($rowSpan == null || $rowSpan == "")
								{
									$rowSpan = 1;
								}
								if ($characterArray[$y][$x]["text"] != null && $characterArray[$y][$x]["text"] != "")
								{
									?>
										<td class="timelineitem" rowspan="<?php print $rowSpan ?>">
											<div class="block">
												<?php print $characterArray[$y][$x]["text"] ?>
											</div>
											<div class="block">
												<a href="javascript: addItemBeforeThisOne('timelineItem', <?php print $characterArray[$y][$x]["sortOrder"] ?>, <?php print $characterArray[$y]["id"] ?>);" class="addbutton"></a>
												<a href="javascript: editTimelineItem(<?php print $characterArray[$y][$x]["id"] ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, <?php print $rowSpan ?>);" class="newbutton"></a>
												<a href="javascript: deleteItem(<?php print $characterArray[$y][$x]["id"] ?>, '<?php print $currentUrl ?>')" class="delete padding">X</a>
												<!--
												<a href="javascript: setRowSpan(<?php print $characterArray[$y][$x]["id"] ?>, <?php print $rowSpan + 1 ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, '<?php print $currentUrl ?>')" class="">+</a>
												<?php 
													if ($rowSpan > 1)
													{
														?>
															<a href="javascript: setRowSpan(<?php print $characterArray[$y][$x]["id"] ?>, <?php print $rowSpan - 1 ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, '<?php print $currentUrl ?>')" class="">-</a>
														<?php
													}
												?>
												-->
											</div>
										</td>
									<?php
								}
								else if ($characterArray[$y][$x]["text"] === "")
								{
									?>
										<td class="emptytimelineitem" rowspan="<?php print $rowSpan ?>">
											<a href="javascript: addItemBeforeThisOne('timelineItem', <?php print $characterArray[$y][$x]["sortOrder"] ?>, <?php print $characterArray[$y]["id"] ?>);" class="addbutton"></a>
											<a href="javascript: editTimelineItem(<?php print $characterArray[$y][$x]["id"] ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, <?php print $rowSpan ?>);" class="newbutton"></a>
											<a href="javascript: deleteItem(<?php print $characterArray[$y][$x]["id"] ?>, '<?php print $currentUrl ?>')" class="delete padding">X</a>
											<!--
											<a href="javascript: setRowSpan(<?php print $characterArray[$y][$x]["id"] ?>, <?php print $rowSpan + 1 ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, '<?php print $currentUrl ?>')" class="">+</a>
											<?php 
												if ($rowSpan > 1)
												{
													?>
														<a href="javascript: setRowSpan(<?php print $characterArray[$y][$x]["id"] ?>, <?php print $rowSpan - 1 ?>, '<?php print $characterArray[$y][$x]["text"] ?>', <?php print $characterArray[$y][$x]["sortOrder"] ?>, '<?php print $currentUrl ?>')" class="">-</a>
													<?php
												}
											?>
											-->
										</td>
									<?php
								}
								else
								{
									?>
										<td class="notimelineitem">
										</td>
									<?php
								}
							}
						?>
					</tr>
				<?php
			}
			?>
				<tr>
					<td align="center">
						<div class="mainbuttons">
							<a href="javascript: showTimeLineDiv('timelineChapter', <?php print $bookId ?>, '', '<?php print $currentUrl ?>')">Add chapter</a>
						</div>
					</td>
					<?php
						for ($y = 0; $y < count($characterArray); $y = $y + 1)
						{	
							$newSortOrder = $characterArray[$y]["sortOrder"] + 1;
							
							if ($newSortOrder == null || $newSortOrder == "" || $newSortOrder == 10000)
							{
								$newSortOrder = 1;
							}
							?>
								<td align="center">
									<div class="mainbuttons">
										<a href="javascript: showTimeLineDiv('timelineItem', <?php print $characterArray[$y]["id"] ?>, <?php print $newSortOrder ?>, '<?php print $currentUrl ?>')">Add</a>
									</div>
								</td>
							<?php						
						}
					?>
				</tr>
			<?php
		?>
	</table>
	<div id="timelineDiv" style="display: none;">
		<textarea id="dataText" class="" name="data_text"></textarea>
		<div class="mainbuttons">
			<a id="insertBeforeSaveButton" href="javascript: saveNewChild('<?php print $currentUrl ?>')">Save</a>
			<a id="updateItemSaveButton" href="javascript: saveContent();">Save</a>
			<a href="javascript: showHideDiv('timelineDiv')">Close</a>
		</div>
	</div>
</div>