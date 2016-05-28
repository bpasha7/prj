<?php
class Dashboard_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function xhrGetListings()
    {
        $sth = $this->database->prepare("SELECT * FROM img");
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $data= $sth->fetchAll();
        echo json_encode($data);
    }

    function xhrInsert()
    {
    	$text = $_POST['text'];
    	$sth = $this->database->prepare('INSERT INTO img(test) VALUES(:text)');
        $sth->execute(array(':text'=> $text));
        $data = array('text'=> $text,'id'  => $this->database->lastInsertId());
 		echo json_encode($data);
        header('Location: ../dashboard/xhrGetListings');
    }
    public function xhrDeleteListing()
    {
        $id = $_POST['id'];
        $sth= $this->database->prepare('DELETE FROM img WHERE ID = "'.$id.'"');
        $sth->execute();
    }
}
?>