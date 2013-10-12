	$selectedCategory 	= $_REQUEST["selectedCategory"];
	$numberOfContents  	= countEntities(array("news"), array(), "johan");
	
	if ($selectedCategory == null || $selectedCategory == "")
	{
		$selected = "class=\"selected\"";
	}
	else
	{
		$selected = "";
	}
?>
<div id="categoryList">
	<h3>Kategorier</h3>
	<a <?php print $selected ?> href="index.php" title="Visa alla inl&auml;gg oavsett kategori">Alla inl&auml;gg (<?php print $numberOfContents ?>)</a>
	<?php
		$result = getUsedTags(getConfigProperty("siteId"));
	
		while (list ($id, $name, $count) = mysql_fetch_row ($result))
		{
			if ($id == $selectedCategory)
			{
				$selected = "class=\"selected\"";
			}
			else
			{
				$selected = "";
			}
			print "<a $selected href=\"index.php?selectedCategory=" . $id . "\" title=\"Visa enbart inl&auml;gg fr&aring;n kategorin " . $name . "\">" . $name . " ($count)</a>";
		}
	?>
</div>