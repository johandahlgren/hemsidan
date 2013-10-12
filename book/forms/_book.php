<?php
	if ($_REQUEST["user_action"] != "delete" && $_REQUEST["user_action"] != "new" && ($bookId != null && $bookId != ""))
	{
		$sqlQuery 	= "SELECT id, data FROM jb_content WHERE id = " . $bookId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		$id 		= $result[0];
		$data		= $result[1];
		
		$name		= getValueFromString("name", $data);
		$aka		= getValueFromString("aka", $data);
		$synopsis	= getValueFromString("synopsis", $data);
		$notes		= getValueFromString("notes", $data);
		
		
		$sqlQuery2 	= "SELECT id, data, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $bookId . " AND c.type = \"chapter\" ORDER BY c.sort_order, c.id;";
		$result2 	= dbGetMultipleRows($sqlQuery2);
		$fullText	= "";
		
		while (list ($id2, $data2, $sortOrder2) = mysql_fetch_row($result2))
		{
			$text 		= getValueFromString("text", $data2);
			$fullText	= $fullText . $text;
		}
		
		$wordArray 			= explode(" ", $fullText);
		$numberOfWords 		= count($wordArray);
	}
	
	if ($userAction == "new" || ($bookId != null && $bookId != ""))
	{
		?>
			
		<?php
	}
	else
	{
		printTabWelcomeMessage("book");	
	}
?>

<div id="maincontent" class="dropshadow">
	<div class="contentcontainer">
		<div class="left66percent">
			<div class="block">
				<div class="fieldname">Name</div>
				<input type="text" class="fieldnarrow" name="data_name" value="<?php print getValueFromString("name", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">A.K.A</div>
				<input type="text" class="fieldnarrow" name="data_aka" value="<?php print getValueFromString("aka", $data) ?>" />
			</div>
			<div class="block">
				<div class="fieldname">Synopsis</div>
				<textarea class="fieldnarrow fieldlow" name="data_synopsis"><?php print getValueFromString("synopsis", $data) ?></textarea>						
			</div>
			<div class="block">
				<div class="fieldname">Notes</div>
				<textarea class="fieldnarrow fieldlow" name="data_notes"><?php print getValueFromString("notes", $data) ?></textarea>						
			</div>
			<div class="block">
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
		</div>
		<div class="right">
			<div class="fieldname">Image</div>
			<?php 
				printImage("image", getValueFromString("image", $data), getValueFromString("name", $data));
			?>						
		</div>
	</div>
	
	<div class="block mainbuttons">
		<a href="javascript: setupDelete(); ajaxSave('user_action=display_book_list&user_id=<?php print $_SESSION["_book_userId"] ?>&div_id=leftMenuDiv')" class="deletebutton">&nbsp;</a>												
		<div class="center">
			<a href="javascript: ajaxSave('user_action=display_book_list&user_id=<?php print $_SESSION["_book_userId"] ?>&div_id=leftMenuDiv');" class="button">Save</a>
		</div>
	</div>
</div>