<?php
class Lot_Model extends Model
{
    public function __construct()
    {
        Session::init();
        if (!empty($_SESSION['Role'])) {
            $role = Session::get('Role');
            parent::__construct($role,'');
        }
        else
        parent::__construct();
    }
    public function bet($lot)
    {
    	$sth = $this->database->prepare("SELECT * FROM aboutlots WHERE LotId=$lot");
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_LAZY);
		 echo '
        <form id="bet">
        <label>Текущая цена: '.$row['Bid'].'</label><br>
        <label>Ставка</label>
        <input type="number" name="lot" value="'.$lot.'" hidden/>
        <input type="number" name="bet" min="'.$row['Bet'].'" value="'.$row['Bet'].'"><br>
        <input type="submit" value="Сделать ставку">
        </form>';
        $sth->closeCursor();
	}
	public function newbid()
	{
		 if (Session::get('loggedIn') == true) {
            $sth = $this->database->prepare("CALL SetBet(:lot, :userid, :bet)");
            $this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $sth->execute(array(
                    ':lot'=> $_POST['lot'],
                    ':userid'=> Session::get('User'),
                    ':bet'=> $_POST['bet'],
                ));
            $res    = $sth->fetch(PDO::FETCH_ASSOC);
            $status = $res['RESULT'];
            $sth->closeCursor();
            if ( $status == 1 ) {
                $this->bet($_POST['lot']);
            }
            else if($status == 0)
            	echo 'Вашу ставку еще никто не перебил!';
            else if($status == -1)
            	echo 'Вы не можете поднимать ставки на свои лоты!';
        }
        else
        echo 'Вы не авторизованы!';
	}
    public function images($id)
    {
        echo '
        <div id="images">';
        $dir = realpath($_SERVER['DOCUMENT_ROOT']).'\public\data\\'.$id.'\\';
        $dh  = opendir($dir);
        //$linksname = array();
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }

        rsort($files);
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] != "." && $files[$i] != ".." && !is_dir($dir.$files[$i])) {
                echo '<img id="image'.$i.'" src="'.URL.'public/data/'.$id.'//'.$files[$i].'"/>';
            }

        }
        echo '</div>
        <div id="slider">';
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] != "." && $files[$i] != ".." && !is_dir($dir.$files[$i])) {
                //$ii = $i + 1;
                echo '<img rel="image'.$i.'" src="'.URL.'public/data/'.$id.'/thumb/thumb_'.$files[$i].'"/img>';
            }

        }
        echo '</div>';


    }
    public function about($id, $lot)
    {
        echo '
        <div class="tabs">
        <input id="tab1" type="radio" name="tabs" checked hidden>
        <label for="tab1" >Описание</label>

        <input id="tab2" type="radio" name="tabs" hidden>
        <label for="tab2" >Вопросы</label>

        <input id="tab3" type="radio" name="tabs" hidden>
        <label for="tab3" >Продавец</label>
        <section id="content1">';

        $sth    = $this->database->prepare("CALL GetItemInfo( $id )");
        $sth->execute();
        $rows   = $sth->fetch(PDO::FETCH_LAZY);
        $userid = $rows['UserId'];
        $skiping= 0;
        //echo " < h2 > About us</h2><p > ";
        foreach ($rows as $key => $value) {
            if ($skiping > 3 ) {
                switch ($key) {
                    case "Название":
                    echo "<h2>Полная информация</h2><p>";
                    break;
                    case "Коментарий":
                    echo "</p><h3>Коментарий</h3><p>$value</p>";
                    break;
                    default:
                    if ($value != "") {
                        echo "<p>$key: $value</p>";
                    }
                    break;
                }
            }
            $skiping++;
        }
        $sth->closeCursor();
        echo '</section>
        <section id="content2">';
        $this->comments($lot);
        echo '</section>
        <section id="content3">';
        $this->owner($userid);
        echo '</section>
        </div>
        ';
    }
    public function owner($id)
    {
        $sth = $this->database->prepare("SELECT * FROM aboutuser WHERE ID=$id");
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_LAZY);
        echo "<h2>Информация о продавце</h2><p>";
        echo '<p>Имя: '.$row['UserName'].'</p>';
        echo '</p><h3>Дополнительная информация</h3>
        <p>Контактный номер: +7'.$row['Phone'].'</p>
        <p>Товары: '.$row['Товары'].'</p>';
    }
    public function  comments($lot)
    {

        $sth = $this->database->prepare("SELECT * FROM Lotscoments WHERE Lot = $lot");
        $sth->execute();
        //$cls_type = 0;
        echo '<div class="chat">
        <div class="body">

        <ul>';
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {

            echo '<li class="question">
            <a class="thumbnail">
            '.$row['Nicname'].'
            </a>
            <div class="msg">
            <h3>'.$row['UserName'].'</h3>
            <span class="preview">'.$row['Message'].'</span>
            <span class="meta">
            '.$row['When'].' &middot;
            <a id="writeto" rel="'.$row['UserName'].'">Ответить</a>
            </span>
            <div coment="'.$row['ID'].'" lot="'.$lot.'" class="rating">';
            if ($row['voices'] == 1)
            $stars = 0;
            else
            $stars = ceil($row['Stars'] / ($row['voices'] - 1));
            switch ($stars) {
                case 1:
                echo '<span starnum = "5">&#9734</span><span starnum = "4">&#9734</span><span starnum = "3">&#9734</span><span starnum = "2">&#9734</span><span starnum = "1">&#9733</span >';
                break;
                case 2:
                echo '<span starnum = "5">&#9734</span><span starnum = "4">&#9734</span><span starnum = "3">&#9734</span><span starnum = "2">&#9733</span><span starnum = "1">&#9733</span >';
                break;
                case 3:
                echo '<span starnum = "5">&#9734</span><span starnum = "4">&#9734</span><span starnum = "3">&#9733</span><span starnum = "2">&#9733</span><span starnum = "1">&#9733</span >';
                break;
                case 4:
                echo '<span starnum = "5">&#9734</span><span starnum = "4">&#9733</span><span starnum = "3">&#9733</span><span starnum = "2">&#9733</span><span starnum = "1">&#9733</span >';
                break;
                case 5:
                echo '<span starnum = "5">&#9733</span><span starnum = "4">&#9733</span><span starnum = "3">&#9733</span><span starnum = "2">&#9733</span><span starnum = "1">&#9733</span >';
                break;
                default:
                echo '<span starnum = "5">&#9734</span><span starnum = "4">&#9734</span><span starnum = "3">&#9734</span><span starnum = "2">&#9734</span><span starnum = "1">&#9734</span >';
                break;
            }
            echo '</div>
            </div>';
            if ($row['UserId'] == Session::get('User'))
            echo '<img class="del" src ="http://wts.dev//public/images/del.png" rel="'.$row['ID'].'" /img>';
            echo '</li>';
        }
        echo '</ul>
        </div>
        <div class="type_msg">
        <textarea id="typing_text" type="text" class="field-type" placeholder="Введите собщение"></textarea>
        <input id="sent_msg" lot="'.$lot.'"class="msg_submit" type="submit"  value="Задать" />
        </div>
        </div>';
        $sth->closeCursor();
    }
    public function addcomment()
    {
        if (Session::get('loggedIn') == true) {
            //$userid = Session::get('User');
            //How many items does user have
            $sth = $this->database->prepare("CALL addcoment(:msg, :userid, :lot)");
            $this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            if (        $sth->execute(array(
                        ':msg' => $_POST['msg'],
                        ':userid' => Session::get('User'),
                        ':lot' => $_POST['lot']
                    )))
            $this->comments($_POST['lot']);
            //echo 'OK';
            else
            print_r($sth->errorInfo());//echo 'Ошибка создания учетной записи!';
        }
        else
        echo 'Вы не авторизованы!';
        $sth->closeCursor();
    }
    public function deletecomment()
    {

    }
    public function addstars()
    {
        if (Session::get('loggedIn') == true) {
            $sth = $this->database->prepare("CALL addstars(:coment, :count, :userid )");
            $this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $sth->execute(array(
                    ':coment'=> $_POST['comment'],
                    ':count' => $_POST['count'],
                    ':userid'=> Session::get('User')
                ));
            $res    = $sth->fetch(PDO::FETCH_ASSOC);
            $status = $res['RESULT'];
            // echo $res['RESULT'];
            $sth->closeCursor();
            if ( $status != 0 ) {
                $this->comments($_POST['lot']);

            }
            //echo 'OK';
            else
            echo 'NO';
        }
        else
        echo 'Вы не авторизованы!';
    }
}
?>