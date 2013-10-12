	$result			= getEntities(null, "header", $selectedCategory);
	$numberOfRows 	= mysql_num_rows ($result);

	if ($numberOfRows > 0)
	{
		$id				= mysql_result($result , 0, 0);
		$type 			= mysql_result($result , 0, 2);
		$publishDate 	= mysql_result($result , 0, 4);
		$data			= mysql_result($result , 0, 6);
	}
	
	$divToRefresh = "headerDivContainer";
?>

<div id="<?php print $divToRefresh ?>">
	<div id="headerDiv" style="background-image: url('<?php print getValueFromString("image", $data) ?>'); background-position: <?php print getValueFromString("backgroundPosition", $data) ?>">
		<?php
			if (userIsLoggedIn())
			{
				?>
					<div id="buttons_<?php print $id ?>" class="entityAdminButtons">
						<div class="center">
							<?php
								if ($numberOfRows == 0)
								{
									renderNewButton(0, "header", "adminDiv", $divToRefresh);
								}
								renderDeleteButton($id, $divToRefresh);
								renderEditButton($id, "header", "headerDiv", $divToRefresh, "true");
							?>
						</div>
					</div>
				<?php
			}
		?>
		<div class="headerTitle"><?php print getValueFromString("title", $data); ?></div>
		<div class="dahlgrenLink"><span class="dahlgrenLinkText">En del av </span><a href="http://www.dahlgren.tv">Dahlgrens nyheter</a></div>
		<div class="headerSubTitle"><?php print getValueFromString("subTitle", $data); ?></div>
	</div>
</div>

<script type="text/javascript">
	$("#<?php print $divToRefresh ?>").bind("update", function(){
		loadAjaxData("<?php print $divToRefresh ?>", "userAction=loadComponent&amp;componentName=header&amp;entityId=<?php print $id ?>");
	});
</script>