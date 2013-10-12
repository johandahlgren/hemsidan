?>
<div id="searchDiv">
	<form method="get" action="index.php">
		<input id="selectedCategory" type="hidden" name="selectedCategory" value="<?php print $_REQUEST["selectedCategory"] ?>" />
		<input id="searchString" type="text" name="searchString" value="<?php print $_REQUEST["searchString"] ?>" />
		<input type="submit" value="Sök" />
		<input type="button" value="Rensa" onclick="document.location='index.php';"/>
	</form>
</div>
<div id="mainDiv" class="main">
	<div class="clear">
		<div id="newDiv"></div>
		<?php
			if (userIsLoggedIn() && $aCounter == 0)
			{
				?>
					<div id="new_buttons" class="newButtonContainer">
						<div class="center">
							<?php
								renderNewButton($entityId, "news", "newDiv", $divToRefresh);
							?>
						</div>
					</div>
				<?php
			}
		?>
	</div>
</div>

<script type="text/javascript">
	$("#mainDiv").bind("update", function(){
		loadAjaxData("<?php print $divToRefresh ?>", "userAction=loadComponent&componentName=newsList&entityId=<?php print $entityId ?>");
	});
</script>