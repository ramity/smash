<?php
class Smash
{
	public $ho='localhost';
	public $un='root';
	public $pw='';
	public $salt='mlaskegrhkq23u4rasghdof';
	
	function css($url)//link css
	{
		return "<link rel='stylesheet' type='text/css' href='$url'>";
	}
	
	function shq($db,$st,$ar,$re)//short hand query
	{
		try
		{
			$d=new PDO("mysql:host=$this->ho;dbname=$db;charset=utf8",$this->un,$this->pw);
			$d->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$d->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$s=$d->prepare($st);
			if(!empty($ar))
				$s->execute($ar);
			else
				$s->execute();
			if($re)
				return $s->fetchAll();
		}
		catch(PDOException $e)
		{
			error_log($e);
			die('A database error occurred');
		}
	}
	
	function cvar($in)//check variable
	{
		if(isset($_POST[$in])&&!empty($_POST[$in]))
			return true;
		else
			return false;
	}
	
	function cCookie($input)
	{
		if(isset($_COOKIE[$input])&&!empty($_COOKIE[$input]))
			return true;
		else
			return false;
	}
	
	function oneWayHash($input)
	{
		$bits=explode('$',crypt($input,'$6$rounds=5000$'.$this->salt.'$'));
		return $bits[4];
	}
	
	function redirect($url)
	{
		header("Location:$url");
	}
	
	function setToken($input)
	{
		$grab=$this->generateToken($input);
		setcookie('token',$grab['product'],time()+86400,'/','.ramity.com','true','true');
		return $grab;
	}
	
	function generateToken($id)
	{
		$idSize=strlen($id);
		
		$string=substr(hash('sha256',mt_rand()),0,mt_rand(0,99999999999));
		$stringSize=strlen($string);
		
		$location=rand(0,$stringSize);
		
		$bitOne=substr($string,0,$location);
		$bitTwo=substr($string,$location,$stringSize);
		
		$product=$bitOne.$id.$bitTwo;
		
		return $array=['id'=>$id,'product'=>$product,'location'=>$location];
	}
	
	function locateToken($cookieName)
	{
		$sql='SELECT uid FROM sessions WHERE token=:token';
		
		$sqlar=array
		(
			'token'=>$_COOKIE[$cookieName]
		);
		
		$result=$this->shq('App_account',$sql,$sqlar,true);
		
		if(!empty($result))
		{
			$grab=$this->setToken($result[0]['uid']);
			
			$sql='UPDATE sessions SET token=:token,location=:location,ip=:ip WHERE uid=:uid';
		
			$sqlar=array
			(
				'token'=>$grab['product'],
				'location'=>$grab['location'],
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'uid'=>$grab['id']
			);
			
			$this->shq('App_account',$sql,$sqlar,false);
			
			return $result[0]['uid'];
		}
		else
		{
			$this->destroyCookie('token');
			
			return false;
		}
	}
	
	function destroyCookie($cookieName)
	{
		unset($_COOKIE[$cookieName]);
		setcookie($cookieName,null,-1,'/','.ramity.com','true','true');
	}
	
	function addPart($input)
	{
		$GLOBALS['smashCSS'].=$input.'+';
		require('/home/ramity/smash/parts/'.$input);
		//outdated
	}
	
	function buildRq($input)
	{
		return $GLOBALS['apiPath'].'ver/'.$GLOBALS['ver'].'/'.$input;
	}
}

$s=new Smash();
?>