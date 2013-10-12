if (userIsLoggedIn())
{
	?>
		<div id="adminMenu" class="formbuttons">
			<div class="center">
				<div class="adminMenuButton" onclick="showHideAdminButtons();">Knappar<br/>av/p&aring;</div>
				<div class="adminMenuButton" onclick="document.location ='index.php';">Ladda om sidan</div>
				<div class="adminMenuButton" onclick="if (confirm('&Auml;r du s&auml;ker?')) document.location = 'index.php?userAction=logout';">Logga ut<br/>&nbsp;</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php
}
