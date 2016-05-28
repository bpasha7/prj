<?php
class Controller
{
	public function __construct()
	{
		//echo "Это главный контроллер";
		$this->view = new View();
	}
	public function loadModel($name)
	{
		$path = 'models/'.$name.'_model.php';
		if(file_exists($path)){
			require 'models/'.$name.'_model.php';
			$modelName = $name.'_Model';
			$this->model = new $modelName();
		}
	}
}
?>