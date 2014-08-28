<?php
//Path di base del progetto
define('__base_path', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('__http_path','http://localhost');
define('DEBUG', true);
//Creo la sessione, tanto poco ma sicuro la userò
session_start();
//Autoloading
require(__base_path.'system'.DIRECTORY_SEPARATOR.'__autoload.php');
$page = page::build();
$left = left::build();
db::close();
require(__base_path.'views'.DIRECTORY_SEPARATOR.'theme.php');
?>