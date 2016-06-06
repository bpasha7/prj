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
    	$this->model->about($id, $lot);
    	//$this->model->comments($lot);
    	//echo '<div class="clear_content"></div>';
        //$this->view->render('lot/index', TRUE);
    }
    public function addcomment()
    {
			$this->model->addcomment();
	}
	public function addstars()
    {
			$this->model->addstars();
	}
}
?>