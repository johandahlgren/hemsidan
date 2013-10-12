	$result			= getEntities(null, "info", $selectedCategory);
	$numberOfRows 	= mysql_num_rows ($result);

	if ($numberOfRows > 0)
	{
		$id				= mysql_result($result , 0, 0);
		$type 			= mysql_result($result , 0, 2);
		$publishDate 	= mysql_result($result , 0, 4);
		$data			= mysql_result($result , 0, 6);
	}
	
	$divToRefresh = "infoDivContainer";
?>

<div id="<?php print $divToRefresh ?>">
	<?php
		if (userIsLoggedIn())
		{
			?>
				<div id="buttons_<?php print $id ?>" class="entityAdminButtons">
					<div class="center">
						<?php
							if ($numberOfRows == 0)
							{
								renderNewButton(0, "info", "adminDiv", $divToRefresh);
							}
							else
							{
								renderDeleteButton($id, $divToRefresh);
								renderEditButton($id, "info", "infoDiv", $divToRefresh, "true");
							}
						?>
					</div>
				</div>
			<?php
		}
	?> 
	
	<div class="infoTitle"><?php print getValueFromString("title", $data); ?></div>
	<img class="infoImage" src="<?php print getValueFromString("image", $data); ?>" />
	<p>
		<?php print getValueFromString("text", $data); ?>
	</p>
</div>

<script type="text/javascript">
	$("#<?php print $divToRefresh ?>").bind("update", function(){
		loadAjaxData("<?php print $divToRefresh ?>", "userAction=loadComponent&amp;componentName=info&amp;entityId=<?php print $id ?>");
	});
</script>