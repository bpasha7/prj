<?php
class Login_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function run()
	{
		//Вызов функции на проверку существования учетной записи
		$sth=$this->database->prepare("CALL authorization(:login, :password)");
		//Удаление "магическийх ковычек", защита от SQL- иньекций
		$sth->execute(array(
				':login'   =>  mysql_escape_string($_POST['login']),
				':password'=> base64_encode(md5(mysql_escape_string($_POST['password'])))
			));
			$count= $sth->rowCount();	
		//если пользователя найден, заносим данные в сессию
		if($count > 0)
		{
			$data = $sth->fetch(PDO::FETCH_ASSOC);	
			Session::init();
			Session::set('loggedIn', true);		
			Session::set('User', $data['UserID']);
			Session::set('Role',  $data['UserRole']);
			Session::set('UserName',  $data['UserName']);
			echo json_encode($data);
		}
		$sth->closeCursor();
	}
}
?>