<?php
	$charset = $_REQUEST["charset"];
	if ($charset == null || $charset == "")
	{
		//$charset = "utf-8";
	}
	header("Content-Type: text/html; charset=" . $charset);
	
	$charsets = array("utf-8", "iso-8859-1", "windows-1250");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php print $charset ?>">
		<title>Encoding Tester</title>
		<link rel="stylesheet" type="text/css" media="all" href="style.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript">
			function changeEncoding()
			{
				$('#myForm').attr("target", "");
				$('#myForm').attr("action", "encodingTester.php");
				$('#myForm').submit();
			}
			
			function testString()
			{
				$('#myForm').attr("target", "resultIframe");
				$('#myForm').attr("action", "encodingTesterResult.php");
				$('#myForm').submit();
			}
		</script>
	</head>
	<body>
		<a href="index.html" class="backButton">Utils</a>
		<div class="container">
			<form id="myForm" method="POST" action="encodingTester.php" accept-charset="<?php print $charset ?>">
				<input type="hidden" name="charsetOut" value="<?php print $charset ?>" />
			  <div class="center">
			  		<label for="charset">Select input encoding</label>
			  		<?php
			  			foreach ($charsets as $currentCharset)
			  			{
			  				?>
			  					<input id="charset" type="radio" name="charset" value="<?php print $currentCharset ?>" <?php if ($_REQUEST["charset"] == $currentCharset) {?>checked="checked"<?php } ?> onclick="changeEncoding();" /><?php print strtoupper($currentCharset) ?></radio>
			  				<?php
			  			}
			  		
				  		if ($charset != null && $charset != "")
				  		{
				  			?>
				  				<label for="input">Input</label>
							  	<textarea id="input" name="input" class="inputField fullWidth tallField"><?php echo $_REQUEST['input']; ?></textarea>
							  	
							  <div class="center">
							  		<table>
							  			<tr>
							  				<td>
							  					<input class="clearButton" type="button" onclick="$('#input').val(''); $('#input').focus();" value="Clear">
							  				</td>
							  				<td>
										  		<input type="submit" onclick="testString();" value="Test">
										  	</td>
										  </tr>
									</table>
							  </div>
							<?php
				  		}
			  		?> 
			  </div>
			  <?php
			  	if ($charset != null && $charset != "")
		  		{
		  			?>
						  <div class="center">
						  		<label for="charset">Result</label>
					  			<iframe id="resultIframe" class="inputField"></iframe> 
					  		</div>
					  <?php
		  		}
		  		?>
			</form>
		</div>
	</body>
</html>