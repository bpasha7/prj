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
        <div id="grey_screen">
        </div>
        <div id="imgboard" hidden="">';
        echo '<img id="f" src="#"/>
        </div>
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
    public function about($id)
    {
        echo '<section class="tabs">
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

        <div class="content-1">';

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
        echo '</div>

        <div class="content-2">
        </div>
        <!-- <div class="content-3">
        <h2>Portfolio</h2>
        <p></p>
        <h3>Examples</h3>
        <p></p>
        </div>-->
        <div class="content-4">
        <h2>
        Контакты
        </h2>
        <p>
        </p>
        <h3>
        Get in touch
        </h3>
        <p>
        </p>
        </div>
        </div>
        </section>';
    }
    public function owner()
    {

    }
    public function  comments($id)
    {
    	
    	$sth      = $this->database->prepare("SELECT Lotscoments WHERE Lot = ");
        $sth->execute();
        $cls_type = 0;
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
        	}
       /* echo '<div class="chat">
        <div class="body">

        <ul>
        <li class="question">
        <a class="thumbnail">
        NR
        </a>
        <div class="message">
        <h3>'..'</h3>
        <span class="preview">'..'</span>
        <span class="meta">
        '..' &middot;
        <a href="">Ответить</a>
        </span>
        <div coment="'..'" class="rating">
        <span starnum="5">&#9733</span><span starnum="4">&#9734</span><span starnum="3">&#9734</span><span starnum="2">&#9734</span><span starnum="1">&#9734</span>
        </div>
        </div>
        </li>';
        <li class="answer">
        <a class="thumbnail" href="#">
        NR
        </a>
        <div class="content">
        <h3>Nick Roach</h3>
        <span class="preview">hey how are things going on the...</span>
        <span class="meta">
        2h ago &middot;
        <a href="#">Category</a>
        &middot;
        <a href="#">Reply</a>
        </span>
        </div>
        </li>
        <li>
        <a class="thumbnail" href="#">
        NR
        </a>
        <div class="content">
        <h3>Nick Roach</h3>
        <span class="preview">hey how are things going on the...</span>
        <span class="meta">
        2h ago &middot;
        <a href="#">Category</a>
        &middot;
        <a href="#">Reply</a>
        </span>
        </div>
        </li>
        <li>
        <a class="thumbnail" href="#">
        NR
        </a>
        <div class="content">
        <h3>Nick Roach</h3>
        <span class="preview">hey how are things going on the...</span>
        <span class="meta">
        2h ago &middot;
        <a href="#">Category</a>
        &middot;
        <a href="#">Reply</a>
        </span>
        </div>
        </li>      <li>
        <a class="thumbnail" href="#">
        NR
        </a>
        <div class="content">
        <h3>Nick Roach</h3>
        <span class="preview">hey how are things going on the...</span>
        <span class="meta">
        2h ago &middot;
        <a href="#">Category</a>
        &middot;
        <a href="#">Reply</a>
        </span>
        </div>
        </li>      <li>
        <a class="thumbnail" href="#">
        NR
        </a>
        <div class="content">
        <h3>Nick Roach</h3>
        <span class="preview">hey how are things going on the...</span>
        <span class="meta">
        2h ago &middot;
        <a href="#">Category</a>
        &middot;
        <a href="#">Reply</a>
        </span>
        </div>
        </li>*/
        echo '</ul>
        </div>
        </div>';
    }
}
?>