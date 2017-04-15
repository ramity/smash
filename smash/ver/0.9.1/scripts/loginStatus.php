<?php
if($s->cCookie('token'))
{
	$uid=$s->locateToken('token');
	if($uid)
	{
		$sql='SELECT username FROM users WHERE id=:id';
		
		$sqlar=array
		(
			'id'=>$uid
		);
		
		$result=$s->shq('App_account',$sql,$sqlar,true);
		
		$secureId=$uid;
		$secureLogin=true;
		$secureUsername=$result[0]['username'];
	}
	else
	{
		$secureLogin=false;
	}
}
else
{
	$secureLogin=false;
}
?>