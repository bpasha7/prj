<?php
class Login_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function run()
	{
		//echo "ter";
		//$data = array('status' => 'OK','username'=> '456', 'userrole'=> '7777');
 			//echo json_encode($data);
		$sth=$this->database->prepare("SELECT UserID, UserRole, UserName FROM Users WHERE UserMail = :login AND UserPass = MD5(:password)");
		$sth->execute(array(
				':login'   => $_POST['login'],
				':password'=> $_POST['password']
			));
			//$data= $sth->fetchAll();
       // echo json_encode($data);
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		$count= $sth->rowCount();
		
		if($count > 0)
		{
			Session::init();
			Session::set('loggedIn', true);		
			Session::set('User', $data['UserID']);
			Session::set('Role',  $data['UserRole']);
			Session::set('UserName',  $data['UserName']);
			echo json_encode($data);
		}
		else
		{   
		}
	}
}
?>