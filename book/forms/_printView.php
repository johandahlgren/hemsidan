<?php
	$scope = $_REQUEST["scope"];
	$strip = $_REQUEST["strip"];
	
	if ($itemId == null || $itemId == "")
	{
		printTabWelcomeMessage("chapter");
	}
	else
	{
		$sqlQuery 		= "SELECT id, data, sort_order FROM jb_content WHERE id = " . $itemId;

		$result 		= dbGetSingleRow($sqlQuery);
		$counter 		= 1;
		
		$id 			= $result[0];
		$data			= $result[1];
		$sortOrder		= $result[2];
		
		$text 			= getValueFromString("text", $data);
		
		?>
			<div class="printview">
				<input type="hidden" name="sort_order" value="<?php print $sortOrder ?>"/>
				<input type="hidden" name="data_status" value="<?php print getValueFromString("status", $data) ?>"/>
				<input type="hidden" name="data_synopsis" value="<?php print getValueFromString("synopsis", $data) ?>"/>
				<input type="hidden" name="data_image" value="<?php print getValueFromString("image", $data) ?>"/>
				<input type="hidden" name="data_image_alt" value="<?php print getValueFromString("image_alt", $data) ?>"/>
				<input type="hidden" name="data_image_alt2" value="<?php print getValueFromString("image_alt2", $data) ?>"/>
				<input type="hidden" name="data_row_style" value="<?php print getValueFromString("row_style", $data) ?>"/>
							
				<div class="page">
					<h2>Chapter <?php print $_REQUEST["chapter_number"] ?></h2>
					<input type="text" name="data_name" value="<?php print getValueFromString("name", $data) ?>" class="writingareatitle">
					<textarea name="data_text" class="writingarea"><?php print getValueFromString("text", $data) ?></textarea>
					<?php			
					$counter = $counter + 1;
				?>
					<div class="block">
						<?php
							$wordArray 			= explode(" ", getValueFromString("text", $data));
							$numberOfWords 		= count($wordArray);
						?>
						<?php print $numberOfWords  ?> words, or roughly 
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
					<div class="block mainbuttons">
						<div class="center">
							<a href="javascript: ajaxSave('');" class="button">Save</a>
							<a href="index.php?type=chapter&book_id=<?php print $bookId ?>&parent_id=<?php print $parentId ?>&item_id=<?php print $id ?>" class="button" title="Edit this chapter.">Edit chapter</a>
						</div>
					</div>
				</div>
			</div>
			<div id="rightcontent">
				<div class="printviewsynopsis">
					<span class="label">Major characters: </span>
					<span class="info">
						<?php
						$resultDetail = getConnectedContentsByType("majorCharacter", $id, "right");		
						while (list ($detailId, $detailData) = mysql_fetch_row ($resultDetail))
						{
							if (getValueFromString("short_name", $detailData) != "")
							{
								?>
									<a href="index.php?type=majorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("short_name", $detailData) . ", " ?></a>
								<?php
							}
							else
							{
								?>
									<a href="index.php?type=majorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("name", $detailData) . ", " ?></a>
								<?php
							}
						}
						?>
					</span><br/>
					<span class="label"> Minor characters: </span>
					<span class="info">
						<?php
							$resultDetail = getConnectedContentsByType("minorCharacter", $id, "right");		
							while (list ($detailId, $detailData) = mysql_fetch_row ($resultDetail))
							{
								if (getValueFromString("short_name", $detailData) != "")
								{
									?>
										<a href="index.php?type=minorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("short_name", $detailData) . ", " ?></a>
									<?php
								}
								else
								{
									?>
										<a href="index.php?type=minorCharacter&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("name", $detailData) . ", " ?></a>
									<?php
								}
							}
						?>
					</span><br/>
					<span class="label"> Locations: </span>
					<span class="info">
						<?php
							$resultDetail = getConnectedContentsByType("location", $id, "right");		
							while (list ($detailId, $detailData) = mysql_fetch_row ($resultDetail))
							{
								if (getValueFromString("short_name", $detailData) != "")
								{
									?>
										<a href="index.php?type=location&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("short_name", $detailData) . ", " ?></a>
									<?php
								}
								else
								{
									?>
										<a href="index.php?type=location&book_id=<?php print $bookId ?>&parent_id=<?php print $detailId ?>&item_id=<?php print $detailId ?>"><?php print getValueFromString("name", $detailData) . ", " ?></a>
									<?php
								}
							}
						?>
					</span>
					<textarea name="data_synopsis" class="writingareasynopsis"><?php print getValueFromString("synopsis", $data) ?></textarea>
				</div>
				<input type="text" id="synonym" value="Find synonym" class="left"/>
				<a href="javascript: displaySynonyms('run');" class="button left">Find</a>
			</div>
		<?php
	}
?>
			
		