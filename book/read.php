<?php
	header('Content-type: text/html; charset=iso-8859-1');
	
	ob_start( 'ob_gzhandler' );

	include_once "utilityMethods.php";
	
	$sqlQuery 	= "SELECT id, name, sort_order, data FROM jb_content WHERE id = " . $_REQUEST["item_id"] . ";";
	$result 	= dbGetSingleRow($sqlQuery);
	$id 		= $result[0];
	$name		= $result[1];
	$sortOrder	= $result[2];
	$data		= $result[3];
	
	$sqlQueryBook 	= "SELECT name FROM jb_content WHERE id = " . $_REQUEST["book_id"] . ";";
	$resultBook		= dbGetSingleRow($sqlQueryBook);
	$bookName 		= $resultBook[0];
	
	if ($bookName == null || $bookName == "")
	{
		$bookName	= "Unknown book title";
	}
	
	$sqlQueryAuthor = "SELECT jb_user.name FROM jb_user, jb_user_book WHERE jb_user_book.user_id = jb_user.user_id AND jb_user_book.book_id = " . $_REQUEST["book_id"] . ";";
	$resultAuthor	= dbGetSingleRow($sqlQueryAuthor);
	$author 		= $resultAuthor[0];

	if ($author == null || $author == "")
	{
		$author	= "Unknown author";
	}
?>

<!DOCTYPE HTML>

<html lang="en">
	<head>
		<title>The Bookmaker</title>
		<meta name="description" content="The Bookmaker.">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<link rel="shortcut icon" href="skins/images/favicon.png" type="image/png">
		<link type="text/css" href="skins/readstyle.css" rel="stylesheet" media="screen">
		<link type="text/css" href="css/css_pirobox/white/style.css" rel="stylesheet" media="screen" title="white">
		<script type="text/javascript" src="resources/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="resources/jquery.cookie.js"></script>
		<script type="text/javascript" src="resources/piroBox.1_2_min.js"></script>
		
		<script type="text/javascript"> 
			<!--
			$(document).ready(function(){
				$().piroBox({
					my_speed: 300, //animation speed
					bg_alpha: 0.75, //background opacity
					radius: 4, //caption rounded corner
					scrollImage : false, // true == image follows the page _|_ false == image remains in the same open position
							   // in some cases of very large images or long description could be useful.
					slideShow : 'true', // true == slideshow on, false == slideshow off
					slideSpeed : 3, //slideshow 
					pirobox_next : 'piro_next', // Nav buttons -> piro_next == inside piroBox , piro_next_out == outside piroBox
					pirobox_prev : 'piro_prev', // Nav buttons -> piro_prev == inside piroBox , piro_prev_out == outside piroBox
					close_all : '.piro_close,.piro_overlay' // add class .piro_overlay(with comma)if you want overlay click close piroBox
				});

				hideIndexDiv();					
				hideImageDiv();
				splitText();
				displayPage();
			});
			
			var currentPage = 0;
			var chapterName	= "<?php print getValueFromString("name", $data) ?>";
			var chapterText = "<?php print lineBreaksToBr(htmlspecialchars(getValueFromString("text", $data), ENT_QUOTES, "ISO-8859-1")) ?>";
			var pageHeight 	= 590;
			var pageArray 	= new Array();
			var pageCounter = 0;
			
			function splitText()
			{
				displayLoadingLayer();
				
				var textArray 		= new Array();
				var wordArray 		= chapterText.split(".");
				var tempWord		= "";
				var divHeight		= 0;
				var storeString 	= "";
				var safetyCounter	= 0;

				for (i = 0; i < wordArray.length; i ++)
				{
					tempWord = wordArray[i] + ".";

					if (i == 0)
					{
						addText("<h1>" + chapterName + "<\/h1>");
						storeString += "<h1>" + chapterName + "<\/h1>";
					}
					
					/*
					if (tempWord == "&nbsp;&nbsp;&nbsp;&nbsp;#<br/> ")
					{
						tempWord = "<div class=\"divider\"><\/div>";
					}
					*/
					
					tempWord = tempWord.replace("&nbsp;&nbsp;&nbsp;&nbsp;#<br/> ", "<div class=\"divider\"><\/div>");
					
					if (i >= wordArray.length -1)
					{
						tempWord += "<div class=\"chapterDivider\"><\/div>";
						storeString += tempWord;
					}
					
					addText(tempWord);
					
					divHeight = $("#calculationDiv").height();
					
					if (divHeight >= pageHeight || i >= wordArray.length - 1)
					{
						pageArray[pageCounter] 	= storeString;
						tempPageText 			= "";
						storeString 			= "";
						pageCounter ++;
						$("#calculationDiv").html("");
						
						addText(tempWord);
						storeString += tempWord;
					}
					else
					{
						storeString += tempWord;
					}
					if (safetyCounter > 10000)
					{
						alert("Safetymeasure!");
						break;
					}
					safetyCounter ++;
				}
				
				hideLoadingLayer();
			}
			
			function addText(text)
			{
				$("#calculationDiv").append(text);
			}
			
			function displayPage()
			{
				var text1 = "";
				var text2 = "";
				
				$("#wordCount1").html("");
				$("#wordCount2").html("");
				
				if (pageArray[currentPage] != null)
				{
					text1 += "<div class=\"block\" style=\"text-align: justify;\">";
					text1 += pageArray[currentPage];
					text1 += "<\/div>";
				}
				
				if (pageArray[currentPage + 1] != null)
				{
					text2 += "<div class=\"block\" style=\"text-align: justify;\">";
					text2 += pageArray[currentPage + 1];
					text2 += "<\/div>";
				}
				
				$("#page1").html(text1);
				$("#page2").html(text2);

				if (currentPage >= pageArray.length - 2)
				{
					$("#nextButton").fadeOut(200);
				}
				else
				{
					$("#nextButton").fadeIn(200);
				}
				
				if (currentPage == 0)
				{
					$("#previousButton").fadeOut(200);
				}
				else
				{
					$("#previousButton").fadeIn(200);
				}

				$("#pageNumber1").html(parseInt(currentPage) + 1);
				$("#pageNumber2").html(parseInt(currentPage) + 2);
				
				if (pageArray[currentPage] != null)
				{
					$("#wordCount1").html(pageArray[currentPage].split(" ").length + " words");
				}
				if (pageArray[currentPage + 1] != null)
				{
					$("#wordCount2").html(pageArray[currentPage + 1].split(" ").length + " words");
				}
				
				$("#currentPageNumber").html("Page " + (currentPage + 1) + " of " + pageArray.length);
				
				bookmark = $.cookie("bookmark");
				
				if (bookmark != null && bookmark != "")
				{
					if (bookmark > currentPage)
					{
						$("#bookmarkRight").show(0);
						$("#bookmarkLeft").hide(0);
						$("#bookmarkMiddle").hide(0);
					}
					else if (bookmark < currentPage)
					{
						$("#bookmarkRight").hide(0);
						$("#bookmarkLeft").show(0);
						$("#bookmarkMiddle").hide(0);
					}
					else if(bookmark == currentPage)
					{
						$("#bookmarkRight").hide(0);
						$("#bookmarkLeft").hide(0);
						$("#bookmarkMiddle").show(0);
					}
				}
				else 
				{
					$("#bookmarkRight").hide(0);
					$("#bookmarkLeft").hide(0);
					$("#bookmarkMiddle").hide(0);
				}
			}
			
			function displayLoadingLayer()
			{
				$("#loadingLayer").fadeIn(200);
			}
			
			function hideLoadingLayer()
			{
				$("#loadingLayer").fadeOut(200);
			}
			
			function addBookmark()
			{
				$.cookie("bookmark", parseInt(currentPage), { expires: 9999 });
				goToBookmark();
			}
			
			function removeBookmark()
			{
				$.cookie("bookmark", null, { expires: 0 });
				displayPage();
			}
			
			function goToBookmark()
			{
				bookmark = $.cookie("bookmark");
				currentPage = parseInt(bookmark);
				displayPage();
			}
			
			function firstPage()
			{
				currentPage = 0;
				displayPage();
			}
			
			function lastPage()
			{
				currentPage = pageArray.length - 1;
				displayPage();
			}
			
			function displayWordCount(pageNumber)
			{
				var div 	= $("#pageNumber" + pageNumber);
				var offset 	= div.offset();

				$("#wordCount" + pageNumber).css({left: offset.left + 'px', top: (offset.top + 20) + 'px'});
				$("#wordCount" + pageNumber).fadeIn(200);
			}
			
			function hideWordCount(pageNumber)
			{
				$("#wordCount" + pageNumber).fadeOut(200);
			}
			
			function showIndexDiv()
			{
				$("#indexDiv").show();
			}
			
			function hideIndexDiv()
			{
				$("#indexDiv").hide();
			}
			
			function showImageDiv()
			{
				$("#imageDiv").show();
			}
			
			function hideImageDiv()
			{
				$("#imageDiv").hide();
			}
			
			-->
		</script>
	</head>
	<body>
		<div id="loadingLayer"></div>
		<div id="calculationDiv"></div>
		<div id="wordCount1" class="wordCount"></div>
		<div id="wordCount2" class="wordCount"></div>
		<div id="tabLeftOpen" class="tab tabLeft" onclick="showIndexDiv();"><div class="textDiv">Index</div></div>
		<div id="tabRightOpen" class="tab tabRight" onclick="showImageDiv();"><div class="textDiv">Images</div></div>
		<?php
			//---------------------------------------------------
			// Check if the user has access to the selected book
			//---------------------------------------------------
			
			$userHasAccess = true;
			
			if ($bookId != null && $bookId != "")
			{
				$sqlQueryAccess = "SELECT * FROM jb_user_book WHERE book_id = " . $bookId . " AND user_id = \"" . $_SESSION["_book_userId"] . "\";";										
				$resultAccess	= dbGetMultipleRows($sqlQueryAccess);
				
				if(mysql_num_rows($resultAccess) == 0)
				{
					$userHasAccess = false;
				}
			}
			
			if ($userHasAccess || getValueFromString("is_public", $data) == "true" )
			{
				?>
				<div id="leftContent">
					<div class="buttons left">
						
					</div>
					<div class="buttons right">
						<div class="button" onclick="addBookmark();">Add Bookmark</div>
						<div class="button" onclick="removeBookmark();">Remove Bookmark</div>
					</div>
					<div id="bookCover">
						<div id="pageContainer">
							<div id="bookmarkLeft" class="bookmark" onclick="goToBookmark();"></div>
							<div id="bookmarkMiddle" class="bookmark" onclick="goToBookmark();"></div>
							<div id="bookmarkRight" class="bookmark" onclick="goToBookmark();"></div>
							
							<div id="pageLeft" class="page leftPage">
								<div id="pageHeaderLeft"><?php print $bookName ?></div>
								<div id="page1"></div>
								<div id="pageNumber1" class="pageNumber" onmouseover="displayWordCount(1);" onmouseout="hideWordCount(1);"></div>
								<div id="previousButton" onclick="currentPage -= 2; displayPage();" class="pageButton left">&nbsp;</div>
							</div>
							<div id="pageRight" class="page rightPage">
								<div id="pageHeaderRight"><?php print getValueFromString("name", $data) ?></div>
								<div id="page2"></div>
								<div id="pageNumber2" class="pageNumber" onmouseover="displayWordCount(2);" onmouseout="hideWordCount(2);"></div>
								<div id="nextButton" onclick="currentPage += 2; displayPage();" class="pageButton left">&nbsp;</div>
							</div>
						</div>
					</div>
					<div id="footerButtons">
						<div id="firstPageButton" class="button" onclick="firstPage();">First page</div>
						<div id="lastPageButton" class="button" onclick="lastPage();">Last page</div>
					</div>
					<div id="currentPageNumber"></div>
				</div>
					
				<div id="indexDiv" class="sideBar">
					<div id="tabLeftClose" class="tab tabLeft" onclick="hideIndexDiv();"><div class="textDiv">Close</div></div>
					<div class="">
						<?php
							$sqlQuery 		= "SELECT id, name, data FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $bookId . " AND c.type = \"chapter\" ORDER BY c.sort_order ASC, c.id ASC;";
							$result 		= dbGetMultipleRows($sqlQuery);
							
							while (list ($id, $name, $data) = mysql_fetch_row($result))
							{
								if (getValueFromString("is_public", $data) == "true")
								{
									$chapterUrl		= "read.php?type=read&amp;book_id=" . $bookId . "&amp;parent_id=" . $bookId . "&amp;item_id=" . $id;
									?>
										<a href="<?php print $chapterUrl ?>"><div class="indexItem"><?php print $name ?></div></a>
									<?php
								}
							}
						?>
					</div>
				</div>
				
				<div id="imageDiv" class="sideBar">
					<div id="tabRightClose" class="tab tabRight" onclick="hideImageDiv();"><div class="textDiv">Close</div></div>
					<div class="padding">
						<?php
							createImageList("location", $itemId);
							createImageList("majorCharacter", $itemId);
							createImageList("minorCharacter", $itemId);
							createImageList("item", $itemId);
							createImageList("world", $itemId);
						?>
					</div>
				</div>
				<?
			}
			else
			{
				?>
					<script type="text/javascript">
						hideLoadingLayer();
					</script>
					
					<div id="leftContent">
						<div id="contentcontainer">
							<h1>Permission denied</h1>
							This is not a public item.
						</div>
					</div>
				<?php
			}
		?>
	</body>
</html>