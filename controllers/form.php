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
		//echo '<div class="clear_content"> a</div>';
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
		//echo '<div class="clear_content"> a</div>';
	}
	public function registration()
	{
		$this->model->registration();
	}
	public function registrationfields()
	{
		$this->model->registrationfields();
		//echo '<div class="clear_content"> a</div>';
	}
	public function test()
	{
		$this->model->test();
	}

}
?>
