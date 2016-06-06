<?php
class Form_Model extends Model
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
    public function test()
    {
    	$myNumber = 123456.78;

		echo number_format( $myNumber, 2 );

    }
    public function groups()
    {
        //Session::init();
        if (Session::get('loggedIn') == true) {
            //$userid = Session::get('User');
            //How many items does user have
            $sth = $this->database->prepare("SELECT GroupId, GroupName FROM Groups");
            $sth->execute();
        }
        $count = $sth->rowCount();
        if ($count > 0) {
            echo "<option disabled>Выберите пожалуйста группу...</option>";
            while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                echo '<option value="'.$row['GroupId'].'">' . $row['GroupName'] . "</option>";
            }
        }
        else {

        }
        $sth->closeCursor();
    }
    public function registration()
    {
    	$sth = $this->database->prepare("SELECT registration( :Uname, :Umail, :Utel, :Upass )");
    	$this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            if (        $sth->execute(array(
                        ':Uname' => $_POST['username'],
                        ':Umail' => $_POST['email'],
                        ':Utel' => str_replace('-','',$_POST['tel']),
                        ':Upass' => base64_encode(md5($_POST['pass']))
                    ))) 
                    echo 'OK';
                   else
                    print_r($sth->errorInfo());//echo 'Ошибка создания учетной записи!';
    }
    public function registrationfields()
    {
        //echo ' < input id = "item_id" name = "item_id" type = "text" hidden/>';
        echo '<li><label class="form-label">Фамилия Имя</label>
        <input name="username" type="text" class="field-style field-full align-none" placeholder="Введите Фамилию и Имя" required/>
        </li>';
        echo '<li><label class="form-label">E-mail</label>
        <input name="email" type="text" class="field-style field-full align-none" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Введите E-mail" required/>';
        echo '<li><label class="form-label">Номер телефона</label>
        <input name="tel" type="text" class="field-style field-full align-none" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Введите номер телефона Формат: XXX-XXX-XXXX" required/>';
        echo '<li><label class="form-label">Пароль</label>
        <input id="pass1" name="pass" type="password" class="field-style field-full align-none" placeholder="Введите Пароль" required/>';
        echo '<li><label class="form-label">Повторите ввод</label>
        <input id="pass2" type="password" class="field-style field-full align-none" placeholder="Повторите ввод" required/>';
        echo '<li><input id="submit_form" name="reg" type="submit" value="Создать" />
        </li>';
    }
    public function fields($arg)
    {
        // Session::init();
        if (Session::get('loggedIn') == true && $arg != false) {
            $userid = Session::get('User');
            $params = array();
            $paramsname = array();
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
                    if ($i == count($params) - 1) {
                        echo '<li><label class="form-label">'.$params[$i].'</label>
                        <textarea type="text" name="'.$paramsname[$i].'" class="field-style" placeholder="Введите '.strtolower($params[$i]).'" />
                        </li>';
                        break;
                    }
                    echo '<li><label class="form-label">'.$params[$i].'</label>
                    <input type="text" name="'.$paramsname[$i].'" class="field-style field-full align-none" placeholder="Введите '.strtolower($params[$i]).'" />
                    </li>';
                }
                echo '<div id="upload-wrapper">
                <div align="center">
                <h3>Выберите файлы</h3>
                <form id="create_form" class="form-style" onSubmit="return false"/>
                <form action="'.URL.'form/upload" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
                <input type="file" name="files[]" multiple="multiple" id="files">
                <input type="submit"  id="submit-btn" value="Upload" />
                <img src="'.URL.'public/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                </form>
                <div id="progressbox" style="display:none;"><div id="progressbar"></div><div id="statustxt">0%</div></div>
                <div id="output"></div>
                </div>
                </div>';
                echo '<li>
                <input id="submit_form" name="item" type="submit" id="smt" value="Создать" />
                </li>';
            }
        }

    }
    public function createitem()
    {
        // Session::init();

        if (Session::get('loggedIn') == true && Session::get('Role') != 'banned') {
            $paramsname = array();
            $data = array();
            $grp   = $_POST['group'];
            $sth   = $this->database->prepare("CALL ColsName( $grp, 0)");
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
            for ($i = 0;$i < count($paramsname);$i++) {
                //$data[$paramsname[$i]] = $_POST[$paramsname[$i]];
                //echo $data;
                $data[] = $paramsname[$i];
                $data[] = $_POST[$paramsname[$i]];

            }
            $d   = json_encode($data);
            //echo $d;
            $sth = $this->database->prepare("CALL NewItem( :owner, :grp, :titel, :data )");
            if (        $sth->execute(array(
                        ':owner' => Session::get('User'),
                        ':grp' => $grp,
                        ':titel' => $_POST['Titel'],
                        ':data' => $d
                    ))) {
                if (!empty($_SESSION['imgDir'])) {
                    $row                = $sth->fetch(PDO::FETCH_LAZY);
                    $lID                = $row['LastID'];
                    $dir                = Session::get('imgDir');
                    $destination_folder = realpath($_SERVER['DOCUMENT_ROOT']).'\public\data\\'.$lID.'\\';
                    mkdir($destination_folder);
                    mkdir($destination_folder. "thumb\\");
                    // Get array of all source files
                    $files              = scandir($dir);
                    // Identify directories
                    // Cycle through all source files
                    foreach ($files as $file) {
                        if (in_array($file, array(".",".."))) continue;
                        // If we copied this successfully, mark it for deletion
                        if(stripos($file, 'thumb_')!== false && copy($dir.$file, $destination_folder."thumb\\".$file)){
							$delete[] = $dir.$file;
							continue;
						}
                        if (copy($dir.$file, $destination_folder.$file)) {
                            $delete[] = $dir.$file;
                        }

                    }
                    // Delete all successfully - copied files
                    foreach ($delete as $file) {
                        unlink($file);
                    }
                    rmdir($dir);
                    unset($_SESSION['imgDir']);
                    //echo '';
                }
                echo 'OK';
            }
            else
            echo 'Ошибка Добавлении!';
            $sth->closeCursor();
        }
        else {
            echo 'Вы забанены!';
        }

    }
    public function lotfields()
    {
        echo '<input id="item_id" name="item_id" type="text" hidden/>';
        echo '<li><label class="form-label">Цена</label>
        <input name="price" type="text" class="field-style field-full align-none" pattern="\d{0,13}\,\d{2}" placeholder="Введите цену. Формат ***,**" required/>
        </li>';
        echo '<li><label class="form-label">Количество</label>
        <input name="count" type="text" class="field-style field-full align-none" pattern="^[ 0-9]+$" placeholder="Введите число экземпляров" required/>
        </li>';
        echo '<li><label class="form-label">Ставка</label>
        <input name="bet" type="text" class="field-style field-full align-none" pattern="\d{0,13}\,\d{2}" placeholder="Введите ставку. Формат ***,**" required/>
        </li>';
        echo '<li><label class="form-label">Начало торгов</label>
        <input name="date" type="date" class="field-style field-split align-left" placeholder="Выберите начало торгов"/>
        </li>';
        echo '<li><div class="styled-select">
        <select name="days" >
        <option value="" disabled>Выберите длительность...</option>
        <option value="7">7 Дней (Неделя)</option>
        <option value="14">14 Дней (Две недели)</option>
        <option value="30">30 Дней (Месяц)</option>
        </select>
        </div> </li>';
        echo '<div class="styled-select">
        <select name="active" >';
        $sth   = $this->database->prepare("SELECT PrId, PrName FROM Priorities");
        $sth->execute();
        $count = $sth->rowCount();
        if ($count > 0) {
            while ($row = $sth->fetch(PDO::FETCH_LAZY)) {
                echo '<option value="'.$row['PrId'].'">'.$row['PrName'].'</option>';
            }
        }
        $sth->closeCursor();
        echo '</select></div><li>
        <input id="submit_form" name="lot" type="submit" value="Создать" />
        </li>';
    }
    public function createlot()
    {
        if (Session::get('loggedIn') == true && Session::get('Role') != 'banned') {
            $sth   = $this->database->prepare("INSERT INTO Lots VALUES(NULL, :id, :price, :count, :datestart, :days, :active, :bet, :lastprice, :userid )");
            $this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $price = str_replace(',','.',$_POST['price']);
            if (        $sth->execute(array(
                        ':id' => $_POST['item_id'],
                        ':price' => $price,
                        ':count' => $_POST['count'],
                        ':datestart' => $_POST['date'],
                        ':days' => $_POST['days'],
                        ':active' => $_POST['active'],
                        ':bet' => str_replace(',','.',$_POST['bet']),
                        ':lastprice' => $price,
                        ':userid' => NULL
                    )))
            echo 'OK';
            else
            print_r($sth->errorInfo());
            //echo 'Ошибка Добавлении!';
            $sth->closeCursor();
        }
        else {
            echo 'Вы забанены!';
        }
    }
    public function signup()
    {

    }
    //======================Uploading=============================
    public function upload()
    {
        ############ Configuration ##############
        $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
        $max_image_size    = 500; //Maximum image size (height and width)
        $thumb_prefix      = "thumb_"; //Normal thumb Prefix
        $uniqnum           = rand(1,time() / 5000);
        $destination_folder= realpath($_SERVER['DOCUMENT_ROOT']).'\public\data\cache\\'.$uniqnum.'\\'; //upload directory ends with / (slash)
        // Session::init();
        Session::set('imgDir', $destination_folder);
        //create uniq folder
        mkdir($destination_folder);
       // mkdir($destination_folder . "thumb\\");
        $jpeg_quality      = 90; //jpeg quality
        $count             = 1;
        ##########################################
        #####  This function will proportionally resize image #####
        if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            // check $_FILES['ImageFile'] not empty
            if (!isset($_FILES['files'])) {
                die('Image file is Missing!'); // output error when above checks fail.
            }
            foreach ( $_FILES['files']['name'] as $i => $name ) {


                //uploaded file info we need to proceed
                //$image_name = $_FILES['files']['name']; //file name
                $image_size      = $_FILES['files']['size'][$i]; //file size
                $image_temp      = $_FILES['files']['tmp_name'][$i]; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height= $image_size_info[1]; //image height
                    $image_type  = $image_size_info['mime']; //image type
                }else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type
                //as well as creates new image from given file
                switch ($image_type) {
                    case 'image/png':
                    $image_res = imagecreatefrompng($image_temp); break;
                    case 'image/gif':
                    $image_res = imagecreatefromgif($image_temp); break;
                    case 'image/jpeg': case 'image/pjpeg':
                    $image_res = imagecreatefromjpeg($image_temp); break;
                    default:
                    $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name
                    $image_info        = pathinfo($name);
                    $image_extension   = strtolower($image_info["extension"]); //image extension
                    $image_name_only   = strtolower($image_info["filename"]);//file name only, no extension

                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name     = $count . '.' . $image_extension;// $image_name_only. '_' .  rand(0, 9999999999) .
                    $count++;
                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if (normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }
                        /*echo $image_save_folder;
                        echo __DIR__;
                        echo realpath($_SERVER['DOCUMENT_ROOT']);
                        echo getcwd();*/
                        /* We have succesfully resized and created thumbnail image
                        We can now output image to user's browser or store information in the database*/
                        //echo ' < div align = "center" > ';
                        echo '<img src="http://wts.dev/public/data/cache/'.$uniqnum.'\\'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                        //echo ' < br />';
                        /*echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';*/
                        //echo '</div > ';
                    }

                    imagedestroy($image_res); //freeup memory
                }

            }
        }
    }
}
#####  This function will proportionally resize image #####
function normal_resize_image($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality)
{

    if ($image_width <= 0 || $image_height <= 0) {
        return false;
    } //return false if nothing to resize

    //do not resize if image is smaller than max size
    if ($image_width <= $max_size && $image_height <= $max_size) {
        if (save_image($source, $destination, $image_type, $quality)) {
            return true;
        }
    }

    //Construct a proportional size of new image
    $image_scale = min($max_size / $image_width, $max_size / $image_height);
    $new_width   = ceil($image_scale * $image_width);
    $new_height  = ceil($image_scale * $image_height);

    $new_canvas  = imagecreatetruecolor( $new_width, $new_height ); //Create a new true color image

    //Copy and resize part of an image with resampling
    if (imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
        save_image($new_canvas, $destination, $image_type, $quality); //save resized image
    }

    return true;
}

##### This function corps image to create exact square, no matter what its original size! ######
function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality)
{
    if ($image_width <= 0 || $image_height <= 0) {
        return false;
    } //return false if nothing to resize

    if ( $image_width > $image_height ) {
        $y_offset = 0;
        $x_offset = ($image_width - $image_height) / 2;
        $s_size   = $image_width - ($x_offset * 2);
    }else {
        $x_offset = 0;
        $y_offset = ($image_height - $image_width) / 2;
        $s_size   = $image_height - ($y_offset * 2);
    }
    $new_canvas = imagecreatetruecolor( $square_size, $square_size); //Create a new true color image

    //Copy and resize part of an image with resampling
    if (imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)) {
        save_image($new_canvas, $destination, $image_type, $quality);
    }

    return true;
}

##### Saves image resource to file #####
function save_image($source, $destination, $image_type, $quality)
{
    switch (strtolower($image_type)) {
        //determine mime type
        case 'image/png':
        imagepng($source, $destination); return true; //save png file
        break;
        case 'image/gif':
        imagegif($source, $destination); return true; //save gif file
        break;
        case 'image/jpeg': case 'image/pjpeg':
        imagejpeg($source, $destination, $quality); return true; //save jpeg file
        break;
        default: return false;
    }
}
?>