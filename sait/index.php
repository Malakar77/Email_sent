<?php
global $database;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
require_once 'dataBase/Base.php';

if(isset($_GET['s'])){

    $shortCode = htmlspecialchars(strip_tags(trim($_GET['s'])));

    $sql = "SELECT * FROM `short_code` WHERE `short_code` = :shortCode";

    $params = [
        ':shortCode' => $shortCode
    ];

    $users = $database->query($sql, $params);
	
    if( count($users) === 0 ){
        header("Location: ". $_ENV['DOMAIN']);
        exit();
    }

    $id = $users[0]['id_client'];
    $type = $users[0]['link'];

    $sql = 'INSERT INTO `metrika` (`id_client`, `type`, `time`, `created_at`, `updated_at`)
            VALUES (:id_client, :type, now(), now(), now());';

    $paramWebSite = [
        'id_client' => $id,
        'type' => $type,
    ];

    $database->query($sql, $paramWebSite);

    header("Location: ". $users[0]['link'] );
    exit();

}
