<?php
class Login extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//$this->view->render('login / index');
	}
	public function index()
	{
		//require 'models/login_model.php';
		//$model = new Login_Model();
		$this->view->render('login/index', true);
	}
	public function run()
	{
		//$data = array('status' => 'OK','username'=> '456', 'userrole'=> '7777');
 			//echo json_encode($data);
 			//echo "11234";
		$this->model->run();
	}

}
?>