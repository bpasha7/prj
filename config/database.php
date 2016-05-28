<?php
class Database extends PDO
{
	public function __construct($user = 'client', $password = '')
	{
		parent::__construct("mysql:host=127.0.0.1;dbname=auction;charset=utf8", $user, $password);
		//header('Content-type: application/json; charset=utf-8');

		//$db = new PDO('mysql:host=127.0.0.1;dbname=auction;charset=utf8', 'root', 'qwerty');
		//(DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	}
}
?>