<?php
class Menu extends Controller
{
	public function __construct()
	{
		parent::__construct();
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
		//echo '<div class="clear_content"></div>';
	}
	public function auction()
	{
		$this->view->render('menu/auction', TRUE);
		//echo '<div class="clear_content"></div>';
	}
	public function searchlots()
	{
		$this->model->searchlots();
	}
	public function pie()
    {
    	$this->model->pie();
	}
	public function bars()
    {
    	$this->model->bars();
	}
	
}
?>