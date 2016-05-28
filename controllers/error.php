<?php
class Error extends Controller
{
	public function __construct()
	{
		//echo "Контроллер обработки ошибок";
		parent::__construct();
		$this->view->msg = 'Страницы не существует!';
		$this->view->render('error/index');
	}
	  public function index() {
	  	//$this->view->msg = 'Страницы не существует!';
   		$this->view->render('error/index');
  }
}
?>