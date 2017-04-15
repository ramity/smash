<div id="topbar">
	<a id="topbarlogo" href="https://ramity.com/">R</a>
	<?php
	if(!$secureLogin)
	{
	?>
	<a class="topbaritem" id="register" href="https://ramity.com/apps/account/register">Register</a>
	<a class="topbaritem" id="login" href="https://ramity.com/apps/account/login">Login</a>
	<?php
	}
	else
	{
	?>
	<a class="topbaritem" id="register">YOUR NAME GOES HERE</a>
	<?php
	}
	?>
</div>