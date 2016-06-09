<html>
<head>
    <meta charset="UTF-8">
    <title>
        Интернет-аукцион
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
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/plans.css">
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/lot.css">
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/chat.css">
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/auction.css">
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/chart.css">
    <!--     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> -->

    <script type="text/javascript" src="<?php echo URL; ?>public/scripts/jquery-2.2.4.js">
    </script>
    <script type="text/javascript" src="<?php echo URL; ?>public/scripts/jquery.form.js">
    </script>
    <script src="<?php echo URL; ?>public/scripts/general.js">
    </script>
    <script src="<?php echo URL; ?>public/scripts/dashboard.js">
    </script>
    <!--    <script src="<?php echo URL; ?>views/dashboard/scripts/default.js"></script>-->
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
<img id="logo" src="<?php echo URL; ?>public/images/logo.png"/>
<input class="open" id="top-box" type="checkbox" hidden>
<label id="open_userbar" class="btn" for="top-box"  <?php
 if (Session::get('loggedIn') == true) echo 'style="visibility: visible;"'?>>
    <?php
    if (Session::get('loggedIn') == true) echo Session::get('UserName')?>
</label>
<div id="tst" class="top-panel">
<div class="message">
    <h1>
        <label id="userbar_user">
            <?php
            if (Session::get('loggedIn') == true) echo Session::get('UserName')?>
        </label>, на вашем счету
        <label id="userbar_rub">
        </label> &#8381,
        <label id="userbar_dol">
        </label>$ и
        <label id="userbar_bonuses">
        </label> <img  alt="bns" src="<?php echo URL; ?>public/images/bounus_ico.png" width="32px" height="32px" style="vertical-align: middle">
    </h1>
    <h2>
    </h2>
    <label id="my_bets" class="btn_mn">
        Ставки
    </label>
    <label id="my_items" class="btn_mn">
        Мои товары
    </label>
    <label id="my_lots" class="btn_mn">
        Мои лоты
    </label>
    <label id="logout" class="btn_mn">
        Выйти
    </label>
</div>
<div id="userbar_content" class="messagetable">
    <div id="box" class="box" hidden>

        <div class="box-head">
            <h2 id="tbl_name" class="left">
            </h2>
        </div>
        <div class="table">
            <div style="width: auto; height:45%; overflow:auto;">
                <table id="tbl" width="100%" border="0" cellspacing="0" cellpadding="0">
                </table>
            </div>
        </div>
    </div>
</div>

</div>
<div id="login_back" class="back">
</div>
<div id="container">
    <ul id="nav">
        <li>
            <a rel="menu/index">
                Главная
            </a>
        </li>
        <li>
            <a rel="menu/auction">
                Аукцион
            </a>
        </li>
        <li>
            <a
                rel="menu/rules" ">Правила
            </a>
        </li>
        <li>
            <a rel="menu/help">
                Помощь
            </a>
        </li>
        <li>
            <a href="#">
                О нас
            </a>
        </li>
        <?php
        if (Session::get('loggedIn') != true)
        echo '<li><a id="menu_login">Войти</a></li>';
        else
        echo '<li style="display: none;"><a id="menu_login">Войти</a></li>';
        ?>
    </ul
    <br>
</div>


