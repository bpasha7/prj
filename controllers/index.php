<?php
class Index extends Controller
{
	public function __construct()
	{
		parent::__construct();
		//echo "Мы в контроллере INDEX";
	}
	public function index()
	{
		$this->view->render('index/index');
		//echo 'INSIDE INDEX INDEX';
	}

	public function details()
	{
		$this->view->render('index/index');
	}
}
?>