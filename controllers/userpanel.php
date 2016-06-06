<?php
class UserPanel extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->view->render('userpanel/panel', true);
	}
	//информация о пользователе
	public function about()
	{
		$this->model->about();
	}
	//лоты пользователя
	public function lots()
	{
		$this->model->lots();
	}
	//Товары пользователя
	public function items()
	{
		$this->model->items();
	}
	//удаление товара
	public function deleteitem($id)
	{
		$this->model->deleteitem($id);
	}
	public function deletelot($id)
	{
		$this->model->deletelot($id);
	}
	//удаление лота

}
?>