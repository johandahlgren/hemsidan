<?php
	ob_start( 'ob_gzhandler' );
	header("Content-Type: text/html; charset=utf-8"); 
	session_start();
	
	include_once "core.php";
	
	$siteId					= $_REQUEST["siteId"];
	$userAction				= $_REQUEST["userAction"];
	$entityId 				= $_REQUEST["entityId"];
	$parentId				= $_REQUEST["parentId"];	
	$type					= $_REQUEST["type"];
	
	if ($userAction == "insert")
	{
		if ($type == "category")
		{
			$dataArray = createCategory($siteId, $_REQUEST["newCategoryName"]);
			print (json_encode($dataArray));
		}
		else
		{
			$dataArray = createEntity($parentId, $type);
			print (json_encode($dataArray));
		}
	}
	else if ($userAction == "update")
	{
		$dataArray = saveEntity($entityId);
		print (json_encode($dataArray));
	}
	else if ($userAction == "delete")
	{
		if ($type == "category")
		{
			$dataArray = deleteCategory($_REQUEST["categoryId"]);
			print (json_encode($dataArray));
		}
		else
		{
			$dataArray = deleteEntity($entityId);
			print (json_encode($dataArray));
		}
	}
	else if ($userAction == "loadComponent")
	{
		$componentName = $_REQUEST["componentName"];
		renderComponent($componentName);
	}
	
	ob_end_flush();
?>