<?php
    $config_connection = require_once 'config_connection.php'; //inforamtion about connection to datebase in array
    try
    {
        $db = new PDO('mysql:host='.$config_connection['host'].';dbname='.$config_connection['database'].';charset=utf8', 
        $config_connection['user'], $config_connection['password'], [
            PDO::ATTR_EMULATE_PREPARES => false, //protection against sql injection
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //exception mode on
            ]);
    }
    catch (PDOException $error_message)
    {
        //echo $error_message->getMessage(); //admin error style
        exit('Błąd krytyczny bazy danych! Skontaktuj się z Damianem'); //user error style
    }
?>
