<?php
class Lot extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index($id)
    {
    	$lot = substr($id, strripos($id, ':')+1 , strlen($id) - strripos($id,':'));
    	$id = substr($id, 0 ,strripos($id,':'));
    	//echo $id . " ". $lot;
    	$this->model->images($id);
    	$this->model->about($id);
    	//$this->model->comments($lot);
        //$this->view->render('lot/index', TRUE);
    }
}
?>