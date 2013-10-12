<div id="maincontent" class="dropshadow">
	<div class="contentcontainer">
		<h1><?php print getValueFromString("name", $data) ?></h1>
		<div class="numberofwords"> 
			<?php
				$wordArray 			= explode(" ", getValueFromString("text", $data));
				$numberOfWords 		= count($wordArray);
				print $numberOfWords 
			?> 
			words (<?php
				$sqlQuery 		= "SELECT words_per_page FROM jb_user WHERE user_id = \"" . $_SESSION["_book_userId"] . "\";";
				$result 		= dbGetSingleRow($sqlQuery);
				$wordsPerPage 	= $result[0];
				if ($wordsPerPage == 0)
				{
					$wordsPerPage = 300;
				}
				print round($numberOfWords / $wordsPerPage, 1);
			?> 
			 pages at <?php print $wordsPerPage ?> words p.p.)
		</div>
		<div class="block" style="text-align: justify;">
			<?php print insertParagraphs(strip_tags(getValueFromString("text", $data))) ?>
		</div>
	</div>
</div>
