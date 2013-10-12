<?php
	if (isset($_POST['jsonInput']))
	{
		header('Content-Type: application/json; charset=utf-8');
		echo $_POST['jsonInput'];
	} 
	else 
	{
		?>
			<html>
				<head>
					<title>JSON</title>
					<link rel="stylesheet" type="text/css" media="all" href="style.css">
				</head>
				<body>
					<a href="index.html" class="backButton">Utils</a>
					<div class="container">
						<form method="POST" accept-charset="utf-8">
							<label for="jsonInput">Input text</label>
							<textarea name="jsonInput" class="inputField fullWidth tallField"></textarea>
							<input type="submit" value="Skicka!" />
						</form>
					</div>
				</body>
			</html>
		<?php
	}
?>
