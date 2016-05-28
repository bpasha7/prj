<html>
<head>
	<meta charset="UTF-8">
	<title>
		Test
	</title>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet'>
    <!--<link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css">-->
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/dashboard.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/navigation.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/login.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/footer.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/panel.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/home.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/form.css">
	<script src="<?php echo URL; ?>public/scripts/jquery-2.2.3.min.js"></script>
	<script src="<?php echo URL; ?>public/scripts/general.js"></script>
	<script src="<?php echo URL; ?>public/scripts/dashboard.js"></script>
<!--	<script src="<?php echo URL; ?>views/dashboard/scripts/default.js"></script>-->
<!--<?php
 /* if(isset($this->js)) {
   foreach($this->js as $js) {
    echo '<script src="'.URL.'views/'.$js.'"></script>';
   }
  }*/
?>-->

</head>
<body>
<?php Session::init(); ?>
<input class="open" id="top-box" type="checkbox" hidden>
       <label id="open_userbar" class="btn" for="top-box"  <?php if(Session::get('loggedIn') == true) echo 'style="visibility: visible;"'?>> <?php if(Session::get('loggedIn') == true) echo Session::get('UserName')?></label>
        <div id="tst" class="top-panel">
            <div class="message">
                <h1>
                    <label id="userbar_user"><?php if(Session::get('loggedIn') == true) echo Session::get('UserName')?></label>, на вашем счету <label id="userbar_rub"></label> &#8381, <label id="userbar_dol"></label>$ и 
                    <label id="userbar_bonuses"></label> <img  alt="bns" src="<?php echo URL; ?>public/images/bounus_ico.png" width="32px" height="32px" style="vertical-align: middle">
                </h1>
                <h2>
                </h2>
                <label id="my_settings" class="btn_mn">Настройки</label>
                <label id="my_items" class="btn_mn">Мои товары</label>
                <label id="my_lots" class="btn_mn">Мои лоты</label>
                <label id="logout" class="btn_mn">Выйти</label>
            </div>
            <div id="userbar_content" class="messagetable">
            </div>
        </div>
<!--<div id="header">-->
<!--<h1>Главная страница</h1>-->
<div id="login_back" class="back"></div>
<div id="container">
	<ul id="nav">
		<li id="selected"><a href="<?php echo URL; ?>index">Главная</a></li>
		<li><a href="#">Аукцион</a></li>
		<li><a href="<?php echo URL; ?>index">Правила</a></li>
		<li><a id="menu_help">Помощь</a></li>
		<li><a href="#">О нас</a></li>
		<?php
			if(Session::get('loggedIn') != true)
			echo '<li><a id="menu_login">Войти</a></li>';
			else
			echo '<li style="display: none;"><a id="menu_login">Войти</a></li>';
			?>	
	</ul
	<br>
</div>

<!--</div>-->
<div id="content">
</div>
