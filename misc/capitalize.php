<?php
	header('Content-Type: text/html; charset=utf-8');

	$operation 		= $_REQUEST["operation"];
	$groupToModify 	= 0;
	$characterToAdd = "";
	$matchesString	= "";

	if ($_REQUEST["groupToModify"] != null)
	{
		$groupToModify = $_REQUEST["groupToModify"];
	}
	
	if ($_REQUEST["characterToAdd"] != null)
	{
		$characterToAdd = $_REQUEST["characterToAdd"];
	}

	function mb_ucfirst($string) 
	{
		global $matchesString;
		$string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
		return $string;
	}
	
	function replaceCallback($matches)
	{
		$response = "";
		global $operation;
		global $groupToModify;
		global $characterToAdd;
		global $matchesString;

		$stringToFix = $matches[$groupToModify];
		
		if ($operation == "uc")
		{
			$response = strtoupper($stringToFix);
		}
		else if ($operation == "ucfirst")
		{
			$response = mb_ucfirst(mb_strtolower($stringToFix, "UTF-8"));
		}
		else if ($operation == "ucword")
		{
			$response = ucwords($stringToFix);
		}
		else if ($operation == "lc")
		{
			$response = strtolower($stringToFix);
		}
		else
		{
			$response = $stringToFix;
		}
		
		if ($_REQUEST["useHtmlEntities"] == "yes")
		{
			$response = htmlentities($response, ENT_COMPAT | ENT_HTML401, "UTF-8");
		}
		
		$response = $characterToAdd . $response . $characterToAdd;
		
		$matchesString = $matchesString . $stringToFix . " - " . $response . "\n";
			
		return $response;
	}
	
	if (isset($_REQUEST["input"])) 
	{
		if (!isset($_REQUEST["regex"])) 
		{
			//$output = $_REQUEST['input'];
		} 
		else 
		{
			$output = preg_replace_callback("/" . $_REQUEST["regex"] . "/", "replaceCallback", $_REQUEST["input"], -1, &$count);
	  	}
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Capitalize IT</title>
		<link rel="stylesheet" type="text/css" media="all" href="style.css">
	</head>
	<body>
		<a href="index.html" class="backButton">Utils</a>
		<div class="container">
			<!-- "([A-ZÅÄÖ][A-ZÅÄÖ]*)" -->
			<form method="POST" action="" accept-charset="utf-8">
			  <label for="regex">Regex</label>
			    <input type="text" name="regex" class="inputField fullWidth" value="<?php print htmlspecialchars($_REQUEST["regex"]); ?>"/>
			  <br/>
			  <label for="regex">Group to modifiy (if your regexp has more than one group)</label>
			    <input type="text" name="groupToModify" class="inputField fullWidth" value="<?php print $groupToModify ?>"/>
			  <br/>
			  <label for="regex">String to add before and after match (optional)</label>
			    <input type="text" name="characterToAdd" class="inputField fullWidth" value="<?php print htmlspecialchars($characterToAdd) ?>"/>
			  <br/>
			   <label for="regex">Replace special characters with HTML entities</label>
			   	<?php 
			   		if ($_REQUEST["useHtmlEntities"] == "yes")
					{
						?>
							<input type="checkbox" name="useHtmlEntities" value="yes" checked="checked"/>
						<?php
					}
					else
					{
						?>
							<input type="checkbox" name="useHtmlEntities" value="yes"/>
						<?php
					}
				?>
			   	
			  <br/>
			  <label for="input">Input</label>
			  <textarea name="input" class="inputField fullWidth tallField"><?php echo $_REQUEST['input']; ?></textarea>
			  <div class="center">
				  <input type="radio" name="operation" value="uc" <?php if ($_REQUEST["operation"] == "uc") {?>checked="checked"<?php } ?> />Upper case</radio>
				  <input type="radio" name="operation" value="ucfirst" <?php if ($_REQUEST["operation"] == "ucfirst") {?>checked="checked"<?php } ?>/>Upper case first</radio>
				  <input type="radio" name="operation" value="ucword" <?php if ($_REQUEST["operation"] == "ucword") {?>checked="checked"<?php } ?>/>Upper case word</radio>
				  <input type="radio" name="operation" value="lc" <?php if ($_REQUEST["operation"] == "lc") {?>checked="checked"<?php } ?>/>Lower case</radio>
				  <input type="radio" name="operation" value="doNothing" <?php if ($_REQUEST["operation"] == "doNothing") {?>checked="checked"<?php } ?>/>Do nothing</radio>
			  </div>
			  <br/>
			  <div class="center">
			  		<input type="submit" value="Apply" />
			  </div>
			</form>
			
			<?php if ($output != null) { ?>
			  <label for="output">Output</label>
			  <textarea name="output" class="inputField"><?php echo $output; ?></textarea>
			  
			  <div>
			  	<label for="matches">Conversions</label><br/>
			  	<pre><code><?php print htmlspecialchars($matchesString) ?></code></pre>
			  </div>
			<?php } ?>
		</div>
	</body>
</html>