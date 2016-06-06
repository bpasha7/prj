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
        /*echo '<section class="tabs">
        <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />
        <label for="tab-1" class="tab-label-1">
        Описание
        </label>

        <input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
        <label for="tab-2" class="tab-label-2">
        Вопросы
        </label>

        <!--<input id="tab-3" type="radio" name="radio-set" class="tab-selector-3" />
        <label for="tab-3" class="tab-label-3">Work</label>-->

        <input id="tab-4" type="radio" name="radio-set" class="tab-selector-4" />
        <label for="tab-4" class="tab-label-4">
        Продавец
        </label>

        <div class="clear-shadow">
        </div>

        <div id="m_content" class="contents">

        <div class="content-1">';*/
        echo '
        <div class="tabs">
        <input id="tab1" type="radio" name="tabs" checked hidden>
        <label for="tab1" >Описание</label>

        <input id="tab2" type="radio" name="tabs" hidden>
        <label for="tab2" >Вопросы</label>

        <input id="tab3" type="radio" name="tabs" hidden>
        <label for="tab3" >Продавец</label>
        <section id="content1">';

        $sth     = $this->database->prepare("CALL GetItemInfo( $id )");
        $sth->execute();
        $rows    = $sth->fetch(PDO::FETCH_LAZY);

        $skiping = 0;
        //echo " < h2 > About us</h2><p > ";
        foreach ($rows as $key => $value) {
            if ($skiping > 3 ) {
                //if($value != "" || $key == "Коментрарий"){
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
                /*if($key == "Коментрарий"){
                echo "</p><h3>Коментрарий</h3><p$value</p></p>";
                //break;
                }
                else{
                echo "<p>$key: $value</p>";
                }
                }
                else{
                continue;//echo "<p>$key: ---</p>";
                }*/
            }
            $skiping++;
        }
        $sth->closeCursor();
        echo '</section>
        <section id="content2">';
        $this->comments($lot);
        echo '</section>
        <section id="content3">
        </section>
        </div>
        ';
    }
    public function owner()
    {

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
            	$stars = ceil($row['Stars'] / ($row['voices']-1));
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
            if($row['UserId'] == Session::get('User'))
            	echo '<img class="del" src ="http://wts.dev//public/images/del.png" rel="'.$row['ID'].'" /img>';
           echo '</li>';
        }
        echo '</ul>
        </div>
        <div class="type_msg">
        <textarea id="typing_text" type="text" class="field-type" placeholder="Введите собщение"/>
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
    public function addstars()
    {
        if (Session::get('loggedIn') == true) {
            //$userid = Session::get('User');
            //How many items does user have
            $sth = $this->database->prepare("CALL addstars(:coment, :count, :userid )");
            $this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $sth->execute(array(
                    ':coment'=> $_POST['comment'],
                    ':count' => $_POST['count'],
                    ':userid'=> Session::get('User'),
                ));
                $res = $sth->fetch(PDO::FETCH_ASSOC);
                $status = $res['RESULT'];
               // echo $res['RESULT'];
                $sth->closeCursor();
            if ( $status != 0 )
            {
            	$this->comments($_POST['lot']);
            	
            }
            //echo 'OK';
            else
            echo 'NO';
        }
        else
        echo 'Вы не авторизованы!';
        //$sth->closeCursor();
    }
}
?>