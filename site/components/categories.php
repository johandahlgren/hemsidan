	$sqlQuery 			= "SELECT c.id, c.name FROM j3_category c, j3_entityCategory ec WHERE ec.categoryId = c.id AND ec.entityId = " . $_REQUEST["entityId"] . ";";
	$resultCategories 	= dbGetMultipleRows($sqlQuery);
	$counterCategories	= mysql_num_rows($resultCategories);

	if ($counterCategories > 0)
	{
		if($counterCategories > 1)
		{
			$ending = "er";
		}
		?>
			<div class="categories">
				Kategori<?php print $ending ?>:
				<?
					$categoryCounter 	= 0;
					$delimiter 			= "";

					while (list ($categoryId, $categoryName, $categoryImage) = mysql_fetch_row ($resultCategories))
					{
						if ($categoryCounter > 0)
						{
							$delimiter = ", ";
						}
						
						print $delimiter ?><a href="index.php?selectedCategory=<?php print $categoryId ?>" title="Visa inl&auml;gg av typen <?php print $categoryName ?>"><?php print $categoryName ?></a><?php

						$categoryCounter = $categoryCounter + 1;
					}
				?>				
			</div>
		<?php
	}
?>