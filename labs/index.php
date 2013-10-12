<?php include_once "core.php"; ?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="main.css" media="screen" />
		<script type="text/javascript" src="http://dahlgren.tv/johan/cms/javascript/jquery-latest.pack.js"></script>
		<script type="text/javascript" src="javascript.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				loadChildren(0, "#tree");
			});
		</script>
	</head>
	<body>
		<div id="treeContainer">
			<ul id="tree"></ul>
		</div>
		<div id="editDiv"></div>
		<div id="editButtons">
			<select id="entityType" name="entityName" class="editbutton">
				<option value="page">Page</option>
				<option value="article">Article</option>
				<option value="image">Image</option>
				<option value="book">Book</option>
				<option value="chapter">Chapter</option>
			</select>
			<a class="editButton add">+</a>
			<a class="editButton delete">-</a> 
		</div>
	</body>
</html>