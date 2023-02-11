<?php 

class Model {
	
    private static $mysql;
	
	public function getCon() 
	{

		if(!isset(self::$mysql)) {

			try {

				self::$mysql = new PDO('mysql:host='.DATABASE_HOST.';dbname='.DATABASE_NAME.';charset=utf8;', DATABASE_USER, DATABASE_PASS);
				self::$mysql->setAttribute(PDO::ATTR_TIMEOUT, 10);
				return self::$mysql;

			} catch(PDOException $e) {

				die('Error while create mysql connection: '.$e->getMessage());

			} 
			
		}

		return self::$mysql;

	}

	private static $shopmysql;
	
	public function getShopCon($database) 
	{

		if(!isset(self::$shopmysql)) {

			try {

				self::$shopmysql = new PDO('mysql:host='.DATABASE_HOST.';dbname='.$database.';charset=utf8;', DATABASE_USER, DATABASE_PASS);
				self::$shopmysql->setAttribute(PDO::ATTR_TIMEOUT, 10);
				return self::$shopmysql;

			} catch(PDOException $e) {

				die('Error while create mysql connection: '.$e->getMessage());

			} 
			
		}

		return self::$shopmysql;

	}
	
	public function curl($url, $cookies = false, $data = false, $headers = [], $auth = false) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if(count($headers) > 0) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if($auth) curl_setopt($ch, CURLOPT_USERPWD, $auth);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$execute = curl_exec($ch);

		return $execute;
		
	}
	
}
