<?php 

include_once '../config.php';

session_start();

function autoloader ($class) {

	if(file_exists('../controller/'.$class.'.php')) {
		include_once '../controller/'.$class.'.php';
	} else if(file_exists('../model/'.$class.'.class.php')) {
		include_once '../model/'.$class.'.class.php';
	} else if(file_exists('../model/'.$class.'.interface.php')) {
		include_once '../model/'.$class.'.interface.php';
	} else if(file_exists('../view/'.$class.'.php')) {
		include_once '../views/'.$class.'.php';
	} else if($class == 'Core' or $class == 'Controller') {
		include_once '../core/'.$class.'.php';
	} 

}

include_once '../vendor/autoload.php';


spl_autoload_register('autoloader');

$core = new Core();
$core->start();
