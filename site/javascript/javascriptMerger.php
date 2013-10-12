<?php
	header("Content-Type: text/javascript");
	header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 *24 * 365)));
	
	$scriptPaths = array (
		"../../cms/javascript/jquery-latest.pack.js", 
		"../../cms/javascript/nicEdit.js", 
		"../../cms/javascript/javascript.php",
		"javascript.js", 
		"caroufredsel.js", 
		"../prettyphoto/jquery.prettyPhoto.js"
	);

	foreach ($scriptPaths as &$path) 
	{
   		include($path);
	}
?>