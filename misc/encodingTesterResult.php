<?php
	$charset = $_REQUEST["charsetOut"];
	header("Content-Type: text/html; charset=" . $charset);
	$output = $_REQUEST["input"];
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php print $charset ?>">
	</head>
	<body>
		<p>
			<?php print $output ?>
		</p>		
	</body>
</html>