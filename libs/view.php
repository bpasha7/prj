<?php
class View
{
	public function __construct()
	{
		//echo "Это вид";
	}
	public function render($name, $noInclude = false)
	{
		if($noInclude == true)
		{
			require 'views/'.$name.'.php';
		}
		else
		{
			require 'views/header.php';
			require 'views/'.$name.'.php';
			require 'views/footer.php';
		}
	}
}
?>