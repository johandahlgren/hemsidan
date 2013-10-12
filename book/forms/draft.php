<?php
	include_once "../utilityMethods.php";	$skinName = $_SESSION["_book_skin"];	if ($skinName == null || $skinName == "")	{		$skinName = "gray";	}
?>
<html>
	<head>
		<link type="text/css" href="../skins/generic.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="../skins/<?php print $skinName ?>/style.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<div class="print">
			<?php
				$sqlQuery 		= "SELECT id, data, sort_order FROM jb_content c JOIN jb_connection x ON x.content_id_1 = c.id AND x.content_id_2 = " . $_SESSION["bookId"] . " AND c.type = \"chapter\" ORDER BY c.sort_order ASC, c.id ASC;";
				$result 		= dbGetMultipleRows($sqlQuery);
				$counter 		= 1;
				$pageCounter 	= 1;
				
				while (list ($id, $data, $sortOrder) = mysql_fetch_row($result))
				{
					$name			= getValueFromString("name", $data);
					$text 			= getValueFromString("text", $data);
					print "<a name=\"chapter" . $id . "\"></a>";
					print "<h1>" . $name . "</h1>";
					print "<p>" . str_replace("\n", "<br/>", $text) . "</p>";
				}
			?>
		</div>
	</body>
</html>