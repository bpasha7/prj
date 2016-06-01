<?php
class Menu_Model extends Model
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
    public function top()
    {
        $sth      = $this->database->prepare("SELECT ItemId, ItemName, `Группа`, `Цена`, `Кол-во`, `Создано` FROM aboutlots WHERE LotActive = 1");
        $sth->execute();
        $cls_type = 0;
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
            switch ($cls_type) {//<p class="plan-price">'.$row['Группа'].'</p>
                case 0:
                echo '<div class="plan">
                <h2 class="plan-title">'.$row['ItemName'].'</h2>
                <img src="'.URL.'public/data/'.$row['ItemId'].'/thumb_1.jpg"></img>
                <ul class="plan-features">
                <li><strong>'.$row['Цена'].'</strong> &#8381</li>
                <li><strong>'.$row['Кол-во'].'</strong>шт.</li>
                <li><strong>'.$row['Создано'].'</strong> Создано</li>
                </ul>
                <a href="#" class="plan-button">Перейти</a>
                </div>';
                $cls_type = 1;
                break;
                case 1:
                echo '<div class="plan plan-tall">
                <h2 class="plan-title">'.$row['ItemName'].'</h2>
                <img src="'.URL.'public/data/'.$row['ItemId'].'/thumb_1.jpg"></img>
                <ul class="plan-features">
                <li><strong>'.$row['Цена'].'</strong> &#8381</li>
                <li><strong>'.$row['Кол-во'].'</strong>шт.</li>
                <li><strong>'.$row['Создано'].'</strong> Создано</li>
                </ul>
                <a href="#" class="plan-button">Перейти</a>
                </div>';
                $cls_type = 0;
                break;
                default: break;
            }
        }
    }
}
?>