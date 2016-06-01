<?php
class Menu extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//echo "Мы HELP";
		//$this->view->render('help / index');
	}
	public function help()
	{
		$this->view->render('menu/help', TRUE);
	}
	public function index()
	{
		$this->view->render('menu/index', TRUE);
	}
	public function rules()
	{
		$this->view->render('menu/rules', TRUE);
	}
	public function top()
	{
		$this->model->top();
	}
	public function other($arg = false)
	{
		//echo "1234";
		//echo json_encode(array('status' => 'OK','username'=> '234' ));
		//echo json_encode('{ "status": "OK","username": "456", "userrole": "7777" }');
		echo "Мы в методе other контроллера Help";
		echo "Параметры: ".$arg;
		//require 'models/help_model.php';
		//$model = new Help_Model();
	}
}
?>