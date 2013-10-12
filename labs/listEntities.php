<?php 
	include_once "core.php"; 
	
	$sqlQuery = "SELECT * FROM j4_entity WHERE parent_id = " . $_REQUEST["parentId"];
	$result = dbGetMultipleRows($sqlQuery); 
	
	while (list ($id, $name, $type, $parentId, $data) = mysql_fetch_row ($result))
	{
		?>
			<li class="treeNode <?php print $type ?>" data-id="<?php print $id ?>"> 
				<div class="expander closed"></div>
				<div class="nodeName"><?php print $name ?></div>
				<ul id="children<?php print $id ?>">
				</ul>
			</li>
		<?php
	}
?>