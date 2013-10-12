<?php
	session_start();
	include "utilityMethods.php";
	
	if ($returnUrl != null && $returnUrl != "")
	{
		?>
			<html><head><meta http-equiv="refresh" content="0;<?php print $returnUrl ?>"/>
		<?php
	}
	else
	{
		?>
			<html>
				<head>
					<link type="text/css" href="css/ui-lightness/jquery-ui-1.8rc3.custom.css" rel="stylesheet" />
					<link type="text/css" href="style.css" rel="stylesheet" media="screen" />
					
					<script type="text/javascript" src="resources/jquery-1.4.1.min.js"></script> 
					<script type="text/javascript" src="resources/jquery.tablesorter.min.js"></script> 
					<script type="text/javascript" src="resources/jquery-ui-1.8rc3.custom.min.js"></script> 
					<script type="text/javascript" src="resources/ckeditor/ckeditor.js"></script>
					
					<script type="text/javascript" src="javascript/javascript.js"></script>
					
					<script type="text/javascript">
						$(document).ready(function()
						{
							var iFrameHeight = $(top.document).height() - 100;
							$("#mainIFrame", top.document).css("height", iFrameHeight + "px");
						});
					</script>
				</head>
				<body>	
					<form name="inputForm" method="post">
						<fieldset>
							<input type="hidden" name="user_action" value="save">
							<input type="hidden" name="parent_id" value="<?php print $parentId ?>">
							<input type="hidden" name="book_id" value="<?php print $bookId ?>">
							<input type="hidden" name="item_id" value="<?php print $itemId ?>">
							<input type="hidden" name="type" value="<?php print $type ?>">
							<input type="hidden" name="connect" value="">
							<input type="hidden" name="content_id_1" value="">
							<input type="hidden" name="content_id_2" value="">
							<input type="hidden" name="return_url" value="<?php print $returnUrl ?>">

							<?php
								if (handleLogin())
								{
									include("forms/" . $type . ".php");
								}									
							?>
						</fieldset>
					</form>
					<div id="displayDiv"></div>
				</body>
			</html>
		<?php
	}
?>