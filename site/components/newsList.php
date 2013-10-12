	$selectedCategory		= $_REQUEST["selectedCategory"];
	$result					= getEntities(null, "news", $selectedCategory);

	$itemsPerPage 			= 5;
	$selectedPageId			= $_REQUEST["pageIndex"];

	if ($selectedPageId == "")
	{
		$selectedPageId = 0;
	}

	$pageStartNumber 	= $itemsPerPage * $selectedPageId;
	$pageEndNumber		= $itemsPerPage * ($selectedPageId + 1);
	$newsCounter 		= 0;
	$divToRefresh		= "mainDiv";
?>
	<div id="<?php print $divToRefresh ?>" class="main">
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
			
				while (list ($id, $name, $type, $parentId, $publishDate, $sortOrder, $data) = mysql_fetch_row ($result))
				{
					if ($newsCounter >= $pageStartNumber && $newsCounter < $pageEndNumber)
					{
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
														renderDeleteButton($id, $divToRefresh);
														renderEditButton($id, "news", "newsItem" . $id, $divToRefresh, "true");
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
					$newsCounter = $newsCounter + 1;
				}				
			?>
		</div>
	</div>
	
	<script type="text/javascript">
		$("#<?php print $divToRefresh ?>").bind("update", function(){
			loadAjaxData("<?php print $divToRefresh ?>", "userAction=loadComponent&componentName=newsList&entityId=<?php print $entityId ?>");
		});
	</script>