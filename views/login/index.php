<form id="loginForm" action="<?php echo URL; ?>login/run" method="post" >

	<div class="field">
	<a href="#" id="create_account">Регистрация</a><!--<?php echo URL; ?>-->
		<label><img src="<?php echo URL; ?>public/images/usr.png"
width="32px" height="32px" style="vertical-align: middle">Имя пользователя:</label>
		<div class="input"><input type="text" name="login" value="bpa@m.ru" id="login" /></div>
	</div>

	<div class="field">
		<a href="#" id="forgot">Забыли пароль?</a>
		<label><img src="<?php echo URL; ?>public/images/key.png"
width="32px" height="32px" style="vertical-align: middle">Пароль:</label>
		<div class="input"><input type="password" name="password" value="12345" id="password" /></div>
	</div>

	<div class="submit">
		<button type="submit">Войти</button>
		<label id="remember"><input name="" type="checkbox" value="" /> Запомнить меня</label>
	</div>

</form>