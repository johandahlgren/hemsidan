<?php
	//---------------------------
	// Headers
	//---------------------------

	import_request_variables("GPC", "");
	
	//----------------
	// DB connection
	//---------------
	
	$db = mysql_connect("localhost", "dahlgren_user", "ngw0bvxj");
	mysql_select_db("dahlgren_db", $db);

	//--------------------
	// Database functions
	//--------------------
	
	function dbGetSingleRow($aSqlQuery)
	{
		$temp 	= mysql_query($aSqlQuery);
		$result = null;
	
		if ($temp != null)
		{
			if (mysql_num_rows($temp) > 0)
			{
				$result = mysql_fetch_array(mysql_query($aSqlQuery)) or die("An error occured when executing the query: " . $aSqlQuery . " " . mysql_error());
			}
		}
		return $result;
	}
	
	function dbGetMultipleRows($aSqlQuery)
	{
		$result = mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
		return $result;
	}
	
	function dbExecuteQuery($aSqlQuery)
	{
		mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
	}
	
	// Replace '<' and '>' Characters
	
	function replace($input) 
	{
	    //return str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $input);
	    return htmlentities($input);
	}
	
	function printDiff($text1, $text2)
	{
		echo '<div class="code left">';
		echo 'Current Version:<hr><pre>';

		// Line Arrays
		
		$cv = explode("\n", replace($text1)); // Current Version
		$ov = explode("\n", replace($text2)); // Old Version
		
		// Count Lines - Set to Longer Version
		
		$lc = (count($cv) > count($ov)) ? count($cv) : count($ov);
		
		// Fix Mismatched Line Counts
		
		for ($flc = count($ov); $flc != $lc; $flc++) {
		    $ov["$flc"] = '';
		}
		
		for ($l = '0'; $l != $lc; $l++) {
		    // Word Arrays
		    $cw = explode(' ', $cv["$l"]); // Current Version
		    $ow = explode(' ', $ov["$l"]); // Old Version
		
		    // Count Words - Set to Longer Version
		    
		    $wc = (count($cw) > count($ow)) ? count($cw) : count($ow);
		
		    // Fix Mismatched Word Counts
		    
		    for ($fwc = count($ow); $fwc != $wc; $fwc++) {
		        $ow["$fwc"] = '';
		    }
		
		    // If each line is identical, just echo the normal line. If not,
		    // check if each word is identical. If not, wrap colored "<b>"
		    // tags around the mismatched words.
		    
		    if ($cv["$l"] !== $ov["$l"]) {
		    	echo "<span class=\"lineDiff\">";
		        for ($w = '0'; $w != $wc; $w++) {
		            if ($cw["$w"] === $ow["$w"]) {
		                echo html_entity_decode($cw["$w"]);
		                echo ($w != ($wc - 1)) ? ' ' : "\n";
		            } else {
		                echo '<span class="textDiff">' . html_entity_decode($cw["$w"]);
		                echo ($w != ($wc - 1)) ? '</span> ' : "</span>\n";
		            }
		        }
		        echo "</span>";
		    } else {
		        echo html_entity_decode($cv["$l"]) . "\n";
		    }
		}
		
		// Ending HTML Tags
		echo "</pre>\n</div>\n";
		echo '<div class="code left">' . "\nOld Version:<hr>\n<pre>\n";
		
		// Read and Display Old Version
		echo $text2 . "\n";
		echo "</pre>\n</div>";
	}
	
	function getTimestamp()
	{ 
	    $seconds = microtime(true); // false = int, true = float 
	    return round(($seconds * 1000)); 
	} 
	
	if ($_REQUEST["newUrl"] != null)
	{
		$sqlQuery = "INSERT INTO jd_webpageTester (url) VALUES (\"" . $_REQUEST["newUrl"] . "\");";
		dbExecuteQuery($sqlQuery);
	}
	
	if ($_REQUEST["deleteSite"] != null)
	{
		$sqlQuery = "DELETE FROM jd_webpageTester WHERE id = " . $_REQUEST["deleteSite"] . ";";
		dbExecuteQuery($sqlQuery);
	}
?>
<html>
	<head>
		<style type="text/css">
			.diff {background-color: rgb(250, 250, 250);}
			.lineDiff {background-color: rgba(255, 0, 0, 0.5);}
			.textDiff {font-weight: bold; color: rgb(0, 255, 0);}
			.codeContainer {position: absolute; top: 10px; left: 10px; width: 95%; height: 800px; overflow: auto; border: 1px solid black; padding: 10px; background-color: white; min-width: 700px;}
			.code {border: 1px solid rgb(200, 200, 200); width: 45%; margin-right: 10px; overflow: auto; padding: 10px;}
			.closeButton {position: fixed; padding: 10px; text-decoration: none; background-color: rgb(220, 220, 220); border: 1px solid rgb(100, 100, 100); border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); color: rgb(0, 0, 0);}
			.symbol {width: 16px; height: 16px; background-image: url("http://www.okanagan.bc.ca/Assets/Public+Folder+(General+Use)/Digital+Assets+-+Public+Use/Icons/Icon+-+Checkmark.jpg");}
			.diff .symbol {background-image: url("http://simuladorfrutales.seguroagricola.com/design/images/icon-error.png");}
			.diffButton {width: 18px; height: 16px; display: block;background-image: url("http://www.ascellon.com/LSC/images/icon_view_diff_mem.gif");}
			.baselineButton {width: 16px; height: 16px; display: block;background-image: url("http://www.mediaroad.com/images/icons/16x16/stopwatch.gif");}
			.deleteButton {width: 16px; height: 16px; display: block;background-image: url("http://support.rightscale.com/@api/deki/files/3875/=icon_Delete_v1.png");}
			.newBaseline {color: rgb(0, 255, 0);}
			.addDiv {margin: 10px auto; display: table;}
			.infoText {display: table; color: rgb(150, 150, 150); margin: 20px auto;}
		</style>
		<link rel="stylesheet" type="text/css" media="all" href="style.css">
		<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
		<script>
			function toggleDiv(aDivId)
			{
				$("#" + aDivId).toggle();
			}
			
			function setBaseline(aSiteId)
			{
				document.location = "webpageTester.php?setBaselineFor=" + aSiteId;
			}
		</script>
	</head>
	<body>
		<a href="index.html" class="buttonLink backButton">Utils</a>
		<div class="container">
			<form action="webpageTester.php" method="POST">
				<input type="hidden" value="insert" />
				<table class="diffTable">
					<tr>
						<th></th>
						<th>URL</th>
						<th>Time</th>
						<th>Baseline</th>
						<th>Diff</th>
						<th>Set</th>
						<th>Delete</th>
					</tr>
					<?php
						$startTimer 	= getTimestamp();
						$sqlQuery 		= "SELECT id, url, lastCheck, hash, html FROM jd_webpageTester;";
						$result 		= dbGetMultipleRows($sqlQuery);
						$totalCount		= 0;
						$numberOfDiffs	= 0;
							
						while (list ($id, $url, $lastCheck, $oldHash, $oldHtml) = mysql_fetch_row ($result))
						{
							$startTimerInner	= getTimestamp();
							$html	 			= trim(file_get_contents($url));
							$hashedContents 	= hash("md5", $html);
							
							if ($hashedContents != $oldHash)
							{
								$rowClass = "diff";
								$numberOfDiffs = $numberOfDiffs + 1;
								$infoText = "This page has changed since the baseline was stored.";
							}
							else
							{
								$infoText = "No change since baseline.";
							}
							
							if ($_REQUEST["setBaselineFor"] == $id || $_REQUEST["setBaselineFor"] == "all")
							{
								$now = time();
								$sqlQuery = "UPDATE jd_webpageTester SET lastCheck = " . $now . ", hash = \"" . $hashedContents . "\", html = \"" . htmlentities($html) . "\"";
								
								if ($_REQUEST["setBaselineFor"] == $id)
								{
									 $sqlQuery = $sqlQuery . " WHERE id = \"" . $_REQUEST["setBaselineFor"] . "\";";
								}
								else
								{
									$sqlQuery = $sqlQuery . " WHERE id = \"" . $id . "\";";
								}
														
								dbExecuteQuery($sqlQuery);
							}
							$endTimerInner	= getTimestamp();
							?>
							<tr class="<?php print $rowClass ?>">
								<td class="center"><div class="symbol" title="<?php print $infoText ?>"></div></td>
								<td><a href="<?php print $url ?>" target="_blank" title="Visit this page"><?php print $url ?></a></td>
								<td><?php print $endTimerInner - $startTimerInner ?> ms</td>
								<td <?php if ($id == $_REQUEST["setBaselineFor"]) print "class=\"newBaseline\""; ?>>
									<?php print date("Y-m-d H:i", $lastCheck) ?>
								</td>
								<td><a href="javascript: toggleDiv('diff_<?php print $id ?>');" class="diffButton" title="Compare current version with baseline"></a></td>
								<td><a href="javascript: setBaseline(<?php print $id ?>)" class="baselineButton" title="Set a new baseline"></a></td>
								<td><a href="webpageTester.php?deleteSite=<?php print $id ?>" class="deleteButton" title="Delete this site"></a></td>
							</tr>
							<div id="diff_<?php print $id ?>" class="codeContainer" style="display: none;">
								<?php printDiff(replace($html), $oldHtml) ?>
								<a href="javascript: toggleDiv('diff_<?php print $id ?>');" class="buttonLink">CLOSE</a>
							</div>
							<?php
							$totalCount = $totalCount + 1;
						}
						
						$endTimer = getTimestamp();
						
						$totalTime = $endTimer - $startTimer;
					?>
					<tr>
						<td></td>
						<td>
							<input type="text" name="newUrl"/>
						</td>
						<td colspan="3">
							<input type="submit" value="Add" />
						</td>
						<td>
							<a href="javascript: setBaseline('all')" class="baselineButton" title="Set a new baseline for all sites"></a>
						</td>
						<td>
							<a class="buttonLink" href="webpageTester.php">Refresh</a>
						</td>
					</tr>
				</table>
			</form>
			<div class="infoText">
				<?php print $numberOfDiffs ?> page(s) out of <?php print $totalCount ?> have changed. Check took <?php print $totalTime ?> ms
			</div>
		</div>
	</body>
</html>