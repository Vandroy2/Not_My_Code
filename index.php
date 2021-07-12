<?php




use application\core\Router;


spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class.'.php');

	if (isset($path)) {
		require $path;
	}
	
});

session_start();

 $router = new Router; 

 $router -> run();
 

?>