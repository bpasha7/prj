<?php
class Form extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index($tname = false)
	{
		$this->view->render('form/form', true);
	}

	public function groups()
    {
    	$this->model->groups();
   	}
   	public function fields($arg = false)
   	{
		$this->model->fields($arg);
	}	
	public function createitem()
   	{
		$this->model->createitem();
	}
	public function createlot()
   	{
		$this->model->createlot();
	}
	public function upload()
	{
		$this->model->upload();
	}
	public function lotfields()
	{
		$this->model->lotfields();
	}
	public function registration()
	{
		$this->model->registration();
	}
	public function registrationfields()
	{
		$this->model->registrationfields();
	}
	public function test()
	{
		$this->model->test();
	}

}
?>
