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
	public function upload($path)
	{
		$this->model->upload($path);
	}
	public function lotfields()
	{
		$this->model->lotfields();
	}

}
?>
