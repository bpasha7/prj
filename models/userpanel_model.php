<?php
class UserPanel_Model extends Model
{
    public function __construct()
    {
        Session::init();
        $role = Session::get('Role');
        parent::__construct($role,'');
    }
    public function about()
    {
        //Session::init();
        if (Session::get('loggedIn') == true && Session::get('Role') != 'banned') {
            $userid = Session::get('User');
            //How many items does user have
            $sth    = $this->database->prepare("SELECT COUNT(ItemId) AS cnt FROM items
                WHERE OwnerId = :userid");
            $sth->execute(array(
                    ':userid'=> $userid
                ));
            $data = $sth->fetch();
            $info_array ['itemcount'] = $data['cnt'];
            $sth->closeCursor();
            //How many lots does user have
            $sth = $this->database->prepare("SELECT COUNT(lots.ItemId) AS cnt FROM lots, items
                WHERE items.OwnerId = :userid AND lots.ItemId = items. ItemId");
            $sth->execute(array(
                    ':userid'=> $userid
                ));
            $data = $sth->fetch();
            $info_array ['lotcount'] = $data['cnt'];
            $sth->closeCursor();
            //User balance
            $sth = $this->database->prepare("CALL MyBalance( :userid )");
            $sth->execute(array(
                    ':userid'=> $userid
                ));
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            $info_array ['rub'] = $data['Rubles'];
            $info_array ['dol'] = $data['Dollars'];
            $info_array ['bon'] = $data['Bonuses'];
            echo json_encode($info_array);
            $sth->closeCursor();
        }
        else {
            $info_array ['itemcount'] = '??';
            $info_array ['lotcount'] = '??';
            $info_array ['rub'] = '##.##';
            $info_array ['dol'] = '##.##';
            $info_array ['bon'] = '##.##';
            echo json_encode($info_array);
        }
    }
    public function deleteitem($id)
    {
        //Session::init();
        if (Session::get('loggedIn') == true && Session::get('Role') != 'banned') {
            $userid = Session::get('User');
            $sth    = $this->database->prepare("DELETE FROM items WHERE OwnerId = :userid AND ItemId = :itemid");
            if ($sth->execute(array(
                        ':userid'=> $userid,
                        ':itemid'=>$id
                    ))) {
                $dir   = realpath($_SERVER['DOCUMENT_ROOT']).'\public\data\\'.$id.'\\';
                $files = scandir($dir);
                // Delete all successfully - copied files
                foreach ($files as $file) {
                    if (in_array($file, array(".",".."))) continue;
                    unlink($dir.$file);
                }
                rmdir($dir);
                echo 'Товар удален';
            }

            else
            echo 'Ошибка БД';
        }
        else {
            echo 'Опция недоступна! Учетная запись ограничена в пользовании!';
        }
    }
    public function deletelot($id)
    {
        //Session::init();
        if (Session::get('loggedIn') == true && Session::get('Role') != 'banned') {
            $userid = Session::get('User');
            $sth    = $this->database->prepare("DELETE FROM lots WHERE LotId = :lotid");
            if ($sth->execute(array(
                        ':lotid'=>$id
                    ))) {
                echo 'Лот удален';
            }
            else
            echo 'Ошибка БД';
        }
        else {
            echo 'Опция недоступна! Учетная запись ограничена в пользовании!';
        }
    }
    public function items()
    {
        // Session::init();
        if (Session::get('loggedIn') == true) {
            $userid = Session::get('User');
            //How many items does user have
            $sth    = $this->database->prepare("SELECT * FROM aboutitems WHERE OwnerId = :userid");
            $sth->execute(array(
                    ':userid'=> $userid
                ));
            $count = $sth->rowCount();
            echo "<tr>\n
                \t<td>Товар <a id=\"new_item\" rel=\"form\">(Добавить новый)</a></td>\n
                \t<td>Группа</td>\n
                \t<td width=\"110\" class=\"ac\">Управление</td></tr>\n";
            if ($count > 0) {
                $odd = 1;
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                    if ($odd % 2 == 0)
                    echo "<tr>\n";
                    else
                    echo "<tr class=\"odd\">\n";
                    echo
                    "\t<td><h3>".$row->ItemName."</h3></td>\n
                    \t<td>".$row->GroupName."</td>\n
                    \t<td>
                    <a rel=\"".$row->ItemId."\" name=\"".$row->ItemName."\" class=\"ico create\"><span class=\"tooltiptext\">Создать лот</span></a>
                    <a rel=\"".$row->ItemId."\" class=\"ico edit\"><span class=\"tooltiptext\">Изменить</span></a>
                    <a name=\"item\" rel=\"".$row->ItemId."\" class=\"ico del\"><span class=\"tooltiptext\">Удалить</span></a></td></tr>\n";
                    $odd++;
                }
                //echo "</tbody > ";
            }
            else {

            }
            //$data[] = $row;
            // echo json_encode($data);
            $sth->closeCursor();
        }
        else {
        }
    }
    public function lots()
    {
        //Session::init();
        if (Session::get('loggedIn') == true) {
            $userid = Session::get('User');
            //How many items does user have
            $sth    = $this->database->prepare("SELECT * FROM userslots WHERE UserId = :userid");
            $sth->execute(array(
                    ':userid'=> $userid
                ));
            $count = $sth->rowCount();
            if ($count > 0) {
                echo "<tr>\n
                \t<th>Лот</th>\n
                \t<th>Группа</th>\n
                \t<th>Цена</th>\n
                \t<th>Дата создания</th>\n
                \t<th>Тип</th>\n
                \t<th width=\"110\" class=\"ac\">Управление</th></tr>\n";
                $odd = 1;
                while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                    if ($odd % 2 == 0)
                    echo "<tr>\n";
                    else
                    echo "<tr class=\"odd\">\n";
                    echo
                    "\t<td><h3>".$row->ItemName."</h3></td>\n
                    \t<td>".$row->Group."</td>\n
                    \t<td>". number_format( str_replace(',','.',$row->Price), 2 )."&#8381</td>\n
                    \t<td>".$row->Created."</td>\n
                    \t<td>".$row->PrName."</td>\n
                    \t<td>
                    <a  class=\"ico edit\"><span class=\"tooltiptext\">Изменить</span></a>
                    <a  name=\"lot\" rel=\"".$row->LotId."\"class=\"ico del\"><span class=\"tooltiptext\">Удалить</span></a></td></tr>\n";
                    $odd++;
                }
                //echo "</tbody > ";
            }
            else {

            }
            //$data[] = $row;
            // echo json_encode($data);
            $sth->closeCursor();
        }
        else {

        }
    }
}
?>