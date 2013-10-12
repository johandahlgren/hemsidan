	$result				= getEntities(null, "header", $selectedCategory);
	$numberOfRows 		= mysql_num_rows ($result);

	if ($numberOfRows > 0)
	{
		$id				= mysql_result($result , 0, 0);
		$type 			= mysql_result($result , 0, 2);
		$publishDate 	= mysql_result($result , 0, 4);
		$dataHead		= mysql_result($result , 0, 6);
	}

	$selectedCategory	= $_REQUEST["selectedCategory"];
	$result				= getEntities(null, "news", $selectedCategory);
	$divToRefresh 		= "headerDivContainer";
?>

<div id="<?php print $divToRefresh ?>">
	<div id="headerDiv">
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
		<div id="headerCarouselContainer">
			<ul id="headerCarousel">
			<?php
				$counter = 0;
				while (list ($id, $name, $type, $parentId, $publishDate, $sortOrder, $data) = mysql_fetch_row ($result))
				{
					$text = getValueFromString("text", $data);
					$imageUrl = "";
					
					while (strstr($text, "<img src="))
					{
						$temp 		= substr($text, strpos($text, "<img src=") + 10);
						$imageUrl 	= substr($temp, 0, strpos($temp, "\""));
						?>
							<li><div class="sliderItem" style="background-image: url(<?php print $imageUrl ?>);"></div></li>
						<?php
						$counter = $counter + 1;
						if ($counter >= 5)
						{
							break 2;
						}
						$text = substr($temp, 10);
					}
				}
			?>
			</ul>
		</div>
		<div id="carouselControls"></div>
		<div class="dahlgrenLink"><a href="http://www.dahlgren.tv">Dahlgrens nyheter</a></div>
	</div>
</div>
 
<script type="text/javascript">
	$(window).load(function (){
		$("#headerCarousel").carouFredSel({
			circular 		: true,
			infinite		: true,
			swipe        	: {
		        onTouch     : true,
		        onMouse     : true
			},
			items 			: {
				visible 	: 1
			},
			direction 		: "left",
			width			: "100%",
			responsive		: true,
			scroll : {
				fx          : "crossfade",
				items 		: "page",
				duration 	: 1000,
			},
			pagination		: {
				container	: "#carouselControls",
				keys        : true
			},
			auto : {
				play			: true,
				timeoutDuration	: 5000,
				pauseOnHover    : "immediate"
			}
		});
	});
	
	$("#<?php print $divToRefresh ?>").bind("update", function(){
		loadAjaxData("<?php print $divToRefresh ?>", "userAction=loadComponent&amp;componentName=header&amp;entityId=<?php print $id ?>");
	});
</script>