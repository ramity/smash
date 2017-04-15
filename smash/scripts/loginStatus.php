<?php
if($s->cCookie('token'))
{
	$s->locateToken('token');
	$secureLogin=true;
}
else
{
	
}
?>