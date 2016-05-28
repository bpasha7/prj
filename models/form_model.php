<?php
class Form_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function about()
    {
        /*Session::init();
        if(Session::get('loggedIn') == true)
        {
        $userid = Session::get('User');
        //How many items does user have
        $sth = $this->database->prepare("SELECT COUNT(ItemId) AS cnt FROM items
        WHERE OwnerId = :userid");
        $sth->execute(array(
        ':userid'=> $userid
        ));
        }*/
    }
    public function groups()
    {
        Session::init();
        if (Session::get('loggedIn') == true) {
            //$userid = Session::get('User');
            //How many items does user have
            $sth = $this->database->prepare("SELECT GroupId, GroupName FROM Groups");
            $sth->execute();
        }
        $count = $sth->rowCount();
        if ($count > 0) {
        	echo "<option>Выберите пожалуйста группу...</option>";
            while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                echo '<option value="'.$row['GroupId'].'">' . $row['GroupName'] . "</option>";
            }
        }
        else {

        }
        $sth->closeCursor();
    }
    public function fields($arg)
    {
        Session::init();
        if (Session::get('loggedIn') == true && $arg != false) {
            $userid = Session::get('User');
            //$group = $arg;//$_POST['grp'];

            $params = array();
            $paramsname = array();
            //$paramsname[] = 'Titel';

            $sth   = $this->database->prepare("CALL ColsName( $arg, 1)");
            $sth->execute();

            $count = $sth->rowCount();
            if ($count > 0) {
                $skipping = 0;
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                    if ($skipping > 3 ) {
                        $params[] = $row['Field'];
                    }
                    $skipping++;
                }
                $sth->closeCursor();
                //print_r($params);
            }
            $sth = $this->database->prepare("CALL ColsName( :grp, 0)");
            $sth->execute(array(
                    ':grp'=> $arg
                ));
            $count = $sth->rowCount();
            if ($count > 0) {
                $skipping = 0;
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                    if ($skipping > 1 ) {
                        $paramsname[] = $row['Field'];
                    }
                    $skipping++;
                }
                $sth->closeCursor();
                //print_r($paramsname);
                echo '<li><label class="form-label">Название объявления</label>
                    <input type="text" name="Titel" class="field-style field-full align-none" placeholder="Введите название обьявления" />
                    </li>';
                for ($i = 0;$i < count($params);$i++) {
                	if($i == count($params)-1){
						echo '<li><label class="form-label">'.$params[$i].'</label>
                    <textarea type="text" name="'.$paramsname[$i].'" class="field-style" placeholder="Введите '.strtolower($params[$i]).'" />
                    </li>';
                    break;
					}
                    echo '<li><label class="form-label">'.$params[$i].'</label>
                    <input type="text" name="'.$paramsname[$i].'" class="field-style field-full align-none" placeholder="Введите '.strtolower($params[$i]).'" />
                    </li>';
                }
                echo '<li>
						<input type="submit" value="Создать" />
					</li>';
            }
        }

    }
	public function createitem()
    {
		Session::init();
        if (Session::get('loggedIn') == true) {
        	$paramsname = array();
        	$data = array();
        	$grp = $_POST['group'];
        	$sth = $this->database->prepare("CALL ColsName( $grp, 0)");
        	$sth->execute();
            $count = $sth->rowCount();
            if ($count > 0) {
                $skipping = 0;
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                    if ($skipping > 1 ) {
                        $paramsname[] = $row['Field'];
                    }
                    $skipping++;
                }
            }
            $sth->closeCursor();
            for ($i = 0;$i < count($paramsname);$i++){
				//$data[$paramsname[$i]] = $_POST[$paramsname[$i]];
				echo $data;
				$data[] = $paramsname[$i];
				$data[] = $_POST[$paramsname[$i]];
				
			}
			$d = json_encode($data);
			//echo $d;
			$sth = $this->database->prepare("CALL NewItem( :owner, :grp, :titel, :data )");
            if($sth->execute(array(
            		':owner' => Session::get('User'),
                    ':grp' => $grp,
                    ':titel' => $_POST['Titel'],
                    ':data' => $d
                )))
           		echo 'OK';
           	else
           		echo 'NOO';//print $this->errorInfo();  
           	$sth->closeCursor();
        }
    }
    public function lotfields()
    {
		echo '<li><label class="form-label">Название объявления</label>
                    <input type="text" name="Titel" class="field-style field-full align-none" value="NAME" disabled/>
                    </li>';
		echo '<li><label class="form-label">Цена</label>
                     <input name="price" type="text" class="field-style field-full align-none" placeholder="Введите цену />
                    </li>';
    	echo '<li><label class="form-label">Количество</label>
                     <input name="count" type="text" class="field-style field-full align-none" placeholder="Введите число экземпляров />
                    </li>';
        echo '<li><label name="price" class="form-label">Цена</label>
                     <input name="price" type="date" class="field-style field-full align-none"/>
                    </li>';
       	echo '<li><label name="price" class="form-label">Цена</label>
                     <input name="price" type="text" class="field-style field-full align-none" placeholder="Введите цену />
                    </li>';
        echo '<div class="styled-select">
				<select name="days" >
				<option value="">Выберите длительность...</option>
				<option value="7">7 Дней (Неделя)</option>
				<option value="14">14 Дней (Две недели)</option>
				<option value="30">30 Дней (Месяц)</option>
				</select>
			</div>';
		echo '<div class="styled-select">
				<select name="active" >';
		$sth = $this->database->prepare("SELECT PrId, PrName FROM Priorities");
        	$sth->execute();
            $count = $sth->rowCount();
            if ($count > 0) {
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                        echo '<option value="'.$row['PrId'].'">'.$row['PrName'].'</option>';
                    }
            }
            $sth->closeCursor();
        echo '<li>
				<input type="submit" value="Создать" />
			 </li>';
	}
    public function createlot()
    {
    	
    }
    public function signup()
    {

    }
    public function upload($path)
    {
    		
	}
}
?>