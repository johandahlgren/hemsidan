<?php
	header("Content-Type: text/html; charset=UTF-8");
	header('Access-Control-Allow-Origin: *');
	ob_start('ob_gzhandler');
	session_start();
	$_SESSION["rootPath"] = $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER['PHP_SELF']) . "/";
	include_once "cms/core.php";


	$index			= $_REQUEST["index"];
	$category		= $_REQUEST["selectedCategory"];
	$searchString	= $_REQUEST["searchString"];
	
	if ($category != null)
	{
		$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate), ent.sortOrder, ent.data AS items FROM j3_entity ent LEFT JOIN j3_entityCategory entCat ON entCat.entityId = ent.id WHERE entCat.categoryId = " . $category;
	}
	else
	{
		$sqlQuery 	= "SELECT ent.id, ent.name, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate), ent.sortOrder, ent.data AS items FROM j3_entity ent WHERE ";
	}
	
	if ($searchString != null)
	{
		if ($category != null)
		{
			$sqlQuery = $sqlQuery . " AND ";
		}
		
		$sqlQuery = $sqlQuery . " ent.data LIKE '%" . $searchString . "%' AND ";
	}
	
	if ($category != null && $searchString == null)
		{
			$sqlQuery = $sqlQuery . " AND ";
		}
	
	$sqlQuery 		= $sqlQuery . " ent.type = 'news' AND siteId = '" . getConfigProperty("siteId") . "' ORDER BY id DESC LIMIT " . $index . ", " . ($index + 1);
		
	$result 		= dbGetSingleRow($sqlQuery);
		
	if ($result[0] != null)
	{
		$id 			= $result[0];
		$name 			= $result[1];
		$type 			= $result[2];
		$parentId 		= $result[3];
		$publishDate 	= $result[4];
		$sortOrder 		= $result[5];
		$data 			= $result[6];
		$numberOfItems	= $result[7];
		
		$_REQUEST["entityId"] = $id;
	
	?>
		<a id="news<?php print $id ?>"></a>
		<div class="info">
			<div class="infoText">
				<?php print ucfirst(getYMDH($publishDate)) ?>
			</div>
			<div class="infoTextShort">
				<?php print ucfirst(getYMDShort($publishDate)) ?>
			</div>
			<?php
				renderComponent("categories");
			?>
		</div>
		<div id="newsItem<?php print $id ?>" class="entry news">
			<?php
				if (userIsLoggedIn())
				{
					?>
						<div id="buttons_<?php print $id ?>" class="entityAdminButtons">
							<div class="center">
								<?php
									renderDeleteButton($id, "mainDiv");
									renderEditButton($id, "news", "newsItem" . $id, "mainDiv", "true");
								?>
							</div>
						</div>
					<?php
				}
			?>	
			<div class="entryTitle">
				<h2><?php print getValueFromString("title", $data) ?></h2>
			</div>
			<div class="entryContent">
				<div class="entrycontentmaincol">
					<?php print getValueFromString("text", $data); ?>
				</div>
			</div>
			<div id="comments_<?php print $id ?>" class="commentsForEntity">
				<?php renderComponent("comments") ?>
			</div>		
		</div>
	<?php
	}
	ob_end_flush();
?>
