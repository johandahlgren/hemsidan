<?php
	header("Content-Type: text/css");
	header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 *24 * 365)));
	
	$scriptPaths = array (
		"../cms/style/admin.css", 
		"style.css",
		"../site/prettyphoto/prettyPhoto.css"
	);

	foreach ($scriptPaths as &$path) 
	{
   		include($path);
	}
?>