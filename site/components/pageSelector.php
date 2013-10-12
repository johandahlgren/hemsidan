<?php
$itemsPerPage 		= 5;
$selectedPageId		= $_REQUEST["pageIndex"];
if ($selectedPageId == "")
{
	$selectedPageId = 0;
}

if ($_REQUEST["selectedCategory"] != "")
{
	$selectedCategory 	= $_REQUEST["selectedCategory"];
	$categories 		= array($selectedCategory);
}
else
{
	$categories = null;
}

$numberOfEntities	= countEntities(array("news"), $categories, getConfigProperty("siteId"));

$numberOfPages = ceil($numberOfEntities / $itemsPerPage);

if ($numberOfPages > 1)
{
	?>
		<div class="pagesDiv">
			<?php
				if ($selectedPageId > 0)
				{
					?>
						<a href="index.php?pageIndex=<?php print $selectedPageId - 1 . $categoryString ?>" class="pageSelector" title="G&aring; till f&ouml;regående sida">&#65308;</a>
					<?php
				}
				
				for ($i = 0; $i < $numberOfPages; $i = $i + 1)
				{
					if ($selectedPageId == $i)
					{
						$extraClass = "selected";
					}
					else
					{
						$extraClass = "";
					}
					?>
						<a href="index.php?pageIndex=<?php print $i . $categoryString ?>" title="G&aring; till sidan <?print $i + 1 ?>" class="pageSelector <?php print $extraClass ?>"><?print $i + 1 ?></a>
					<?php
				}

				if ($selectedPageId < $numberOfPages -1)
				{
					?>
						<a href="index.php?pageIndex=<?php print $selectedPageId + 1 . $categoryString ?>" class="pageSelector" title="G&aring; till n&auml;sta sida" >&#65310;</a>
					<?php
				}
			?>
		</div>
	<?php
}