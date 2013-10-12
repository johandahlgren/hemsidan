<?php
	if (!isset($_SESSION)) 
	{
		session_start();
	}
		
	if (file_exists("../../cms/core.php"))
	{
		include_once("../../cms/core.php");
	}
?>

<script type="text/javascript">
	function addNewCategory()
	{
		$.ajax({
		  url: "cms/ajaxHandler.php?userAction=insert&type=category&siteId=<?php print getConfigProperty("siteId") ?>&newCategoryName=" + $("#newCategoryName").val(),
		  success: function(){
			$("#categoriesDiv").empty();
			$("#categoriesDiv").load("site/forms/category_form.php?entityId=<?php print $_REQUEST["entityId"] ?>");
		  }
		});
	}
	function deleteCategory(aCategoryId)
	{
		if (confirm("Är du säker?"))
		{
			$.ajax({
			  url: "cms/ajaxHandler.php?userAction=delete&type=category&categoryId=" + aCategoryId,
			  success: function(){
				$("#categoriesDiv").empty();
				$("#categoriesDiv").load("site/forms/category_form.php?entityId=<?php print $_REQUEST["entityId"] ?>");
			  }
			});
		}
	}
</script>

<div class="formLabel">Kategorier:</div>
<div class="clear">
	<?php
		$categoriesOfContent 		= array();
		$result 					= getAllTags(getConfigProperty("siteId"));
		
		if ($_REQUEST["entityId"] != null &&  $_REQUEST["entityId"] != "")
		{
			$sqlQuery 			= "SELECT categoryId FROM j3_entityCategory WHERE entityId = " . $_REQUEST["entityId"] . ";";
			$resultCategories 	= dbGetMultipleRows($sqlQuery);
			
			while (list ($categoryId) = mysql_fetch_row ($resultCategories))
			{
				$categoriesOfContent[] = $categoryId;
			}
		}
		
		while (list ($id, $name, $count) = mysql_fetch_row ($result))
		{
			if (in_array($id, $categoriesOfContent))
			{
				$selected = "checked=\"1\"";
			}
			else
			{
				$selected = "";
			}
			?>
				<div class="left">
					<div class="left">
						<input type="checkbox" class="formcheck" name="category_<?php print $id ?>" value="1" <?php print $selected ?>/>
					</div>
					<div class="left">
						<?php print $name ?>
						<a class="deleteitem" href="#" onclick="javascript: deleteCategory(<?php print $id ?>)">x</a>
					</div>
				</div>
			<?php
		}
	?>
	<div class="addCategoryDiv">
		<div class="left"><input type="text" class="formField smallField" id="newCategoryName" name="newCategoryName" /></div>
		<div class="left"><div class="adminButton" onclick="addNewCategory();">L&auml;gg till</div></div>
	</div>
</div>