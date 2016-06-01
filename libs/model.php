<?php
class Model
{
	public function __construct($user='banned', $password ='')
	{
		$this->database = new Database($user, $password);
	}
}
?>