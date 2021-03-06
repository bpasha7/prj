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
        $sth      = $this->database->prepare("SELECT LotId, ItemId, ItemName, `Группа`, `Цена`, `Кол-во`, Days FROM aboutlots WHERE LotActive = 1 AND Days != 'Торги окончены'");
        $sth->execute();
        $cls_type = 0;
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
            switch ($cls_type) {
                //<p class = "plan - price" > '.$row['Группа'].'</p >
                case 0:
                echo '<div class="plan">
                <h2 class="plan-title">'.$row['ItemName'].'</h2>
                <img src="'.URL.'public/data/'.$row['ItemId'].'/thumb/thumb_1.jpg"></img>
                <ul class="plan-features">
                <li><strong>'.$row['Цена'].'</strong> &#8381</li>
                <li><strong>'.$row['Кол-во'].'</strong>шт.</li>
                <li><strong>'.$row['Создано'].'</strong> Создано</li>
                </ul>
                <a rel="'.$row['ItemId'].'" lot="'.$row['LotId'].'" class="plan-button">Перейти</a>
                </div>';
                $cls_type = 1;
                break;
                case 1:
                echo '<div class="plan plan-tall">
                <h2 class="plan-title">'.$row['ItemName'].'</h2>
                <img src="'.URL.'public/data/'.$row['ItemId'].'/thumb/thumb_1.jpg"></img>
                <ul class="plan-features">
                <li><strong>'.$row['Цена'].'</strong> &#8381</li>
                <li><strong>'.$row['Кол-во'].'</strong>шт.</li>
                <li><strong>'.$row['Days'].'</strong> Осталось дней</li>
                </ul>
                <a rel="'.$row['ItemId'].'" lot="'.$row['LotId'].'" class="plan-button">Перейти</a>
                </div>';
                $cls_type = 0;
                break;
                default: break;
            }
        }

        $sth->closeCursor();
    }
    public function searchlots()
    {
        $pat = $_POST['name'];
        $grp = $_POST['group'];
        //создание шаблона для сортировки, исходя их введенные пользовалелем данных
        if($grp != "")
        	$grp = "AND GroupId = '$grp'";
        $srt = $_POST['sort'];
        if($srt != "")
        	$srt = "ORDER BY 5 $srt";      
         $sth      = $this->database->prepare("SELECT LotId, ItemId, GroupId, ItemName, `Цена`, `Кол-во`, Days FROM aboutlots WHERE
 ItemName AND Days != 'Торги окончены' LIKE '%$pat%' $grp $srt");
        $sth->execute();
        $count= $sth->rowCount();
        //Вывод найденной информации
        if($count > 0 ){
        	  echo '
        <tr>
        <th colspan="2">Лот</th>
        <th>Цена</th>
        <th>До конца торгов(дни)</th>
        <th>Количество</th>
        </tr>';
        while($row = $sth->fetch(PDO::FETCH_LAZY)){
			echo'
			<tr>
        <td><img src="'.URL.'public/data/'.$row['ItemId'].'/thumb/thumb_1.jpg"></td>
        <td><a rel="'.$row['ItemId'].'" lot="'.$row['LotId'].'" class="to_lot">'.$row['ItemName'].'</a></td>
        <td>'.$row['Цена'].'</td>
        <td>'.$row['Days'].'</td>
        <td>'.$row['Кол-во'].'</td>
        </tr>';
		}
			
		}
		else{
			echo '<tr><th>Ничего не найдено=(</th></tr>';
		}
    }
    public function pie()
    {
		$sth      = $this->database->prepare("CALL LotsStat()");
        $sth->execute();
        $stat = array();
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
        	$mystat= new Stat();
			$mystat->label = $row['Group'];
			$mystat->data =  $row['Lots'];
			$stat[] = $mystat;
            }
            $sth->closeCursor();
      echo json_encode($stat);
	}
	public function bars()
    {
		$sth      = $this->database->prepare("CALL CommentStat()");
        $sth->execute();
        $stat = array();
        while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
        	$mystat=array();
			$mystat[] = $row['Day'];
			$mystat[] =  $row['Comemnts'];
			$stat[] = $mystat;
            }
            $sth->closeCursor();
      echo json_encode($stat);
	}
}
class Stat
{
    public $label;
    public $data;
}
?>