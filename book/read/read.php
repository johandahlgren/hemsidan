<?php
	ob_start( 'ob_gzhandler' );
	
	include_once "../utilityMethods.php";
	
	$sqlQuery 	= "SELECT id, name, sort_order, data FROM jb_content WHERE id = " . $_REQUEST["item_id"] . ";";
	$result 	= dbGetSingleRow($sqlQuery);
	$id 		= $result[0];
	$name		= $result[1];
	$sortOrder	= $result[2];
	$data		= $result[3];
?>
<html>
	<head>
		<link type="text/css" href="style.css" rel="stylesheet" media="screen" />
		<script type="text/javascript" src="resources/piroBox.1_2_min.js"></script>
	</head>
	<body style="background-image: url('<?php print getValueFromString("background_image", $data) ?>');">
		<div id="maincontent">
			<?php
				if ($itemId != null && $itemId != "")
				{
					?>
						<div id="rightcontent">
							<?php
								renderList("list", "Locations", "location", $itemId, $bookId, "locationDiv", false, false, false);
								renderList("list", "Major characters", "majorCharacter", $itemId, $bookId, "majorCharacterDiv", false, false, false);
								renderList("list", "Minor characters", "minorCharacter", $itemId, $bookId, "minorCharacteriv", false, false, false);
								renderList("list", "Items", "item", $itemId, $bookId, "itemDiv", false, false, false);
								renderList("list", "World items", "world", $itemId, $bookId, "worldDiv", false, false, false);
							?>
						</div>
					<?php 
				}
			?>
			<div id="contentcontainer">
				<?php
					if (getValueFromString("is_public", $data) == "true")
					{
						?>
							<h1><?php print getValueFromString("name", $data) ?></h1>
							<div class="block" style="text-align: justify;">
								<?php print insertParagraphs(strip_tags(getValueFromString("text", $data))) ?>
							</div>
						<?php
					}
					else
					{
						?>
							<h1>Permission denied</h1>
							This is not a public item.
						<?php
					}
				?>
			</div>
		</div>
	</body>
</html>