<?php
require('../system/config.php');
//Crea la connessione
$db = new mysqli();
//Apertura connessione
$db->connect($db_data['host'], $db_data['user'],$db_data['pass'], $db_data['database']);
//Controllo errori
if($db->connect_errno != 0){
	die ('Impossibile connettersi al database');
}
$db->multi_query(file_get_contents('database.sql'));
$db->close();
?>