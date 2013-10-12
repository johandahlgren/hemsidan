<?php
	ob_start( 'ob_gzhandler' );
	header("Content-Type: text/html; charset=utf-8"); 
	session_start();
	
	include_once("core.php");	
	
	$siteId			= $_REQUEST["siteId"];
	$userAction		= $_REQUEST["userAction"];
	$entityId 		= $_REQUEST["entityId"];
	$parentId		= $_REQUEST["parentId"];	
	$type			= $_REQUEST["type"];
	
	$result			= getEntity($entityId);		
	$data			= $result["data"];
	
	$formAction 	= "formHandler.php";
?>

<div id="editDiv">
	<form id="inputForm" method="post" action="<?php print $formAction ?>" enctype="multipart/form-data">
		<fieldset>
			<input type="hidden" id="siteId" name="siteId" value="<?php print $siteId ?>">
			<input type="hidden" id="userAction" name="userAction" value="<?php print $userAction ?>">
			<input type="hidden" id="entityId" name="entityId" value="<?php print $entityId ?>">
			<input type="hidden" id="parentId" name="parentId" value="<?php print $parentId ?>">
			<input type="hidden" id="type" name="type" value="<?php print $type ?>">
			<input type="hidden" id="adminDivId" name="adminDivId" value="">
			<input type="hidden" id="originatingDivId" name="originatingDiv" value="">
			<input type="hidden" id="editInline" name="editInline" value="">
			
			<div class="editForm">
			<?php 
				includeForm($type, $data);
			?>	
			</div>
		</fieldset>
	</form>
</div>