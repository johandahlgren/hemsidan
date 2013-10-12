<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="style/admin.css">
	<script type="text/javascript" src="javascript/jquery-latest.pack.js"></script>
	<script type="text/javascript" src="javascript/javascript.php"></script>
</head>
<body>
	<div id="loginDiv">
		<form id="loginForm" method="post" action="../index.php">
			<input type="hidden" name="userAction" value="login" />
			<div class="formLabel">Anv&auml;ndar-ID:</div>
			<input type="text" class="formField" name="cmsUserId" />
			<div class="formLabel">L&ouml;senord:</div>
			<input type="password" class="formField" name="cmsPassword" />
			<div class="center">
				<div class="adminButton loginButton" onclick="document.getElementById('loginForm').submit();">Logga in</div>
			</div>
			<div class="clear"></div>
		</form>
	</div>
	<a href="../index.php" class="loginSiteLink">Till siten</a>
	<script type="text/javascript">
		focusFirstField();
	</script>
</body>