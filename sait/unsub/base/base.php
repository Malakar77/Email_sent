<?php
global $database;
require __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../dataBase/Base.php';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $data = json_decode(file_get_contents("php://input"), false);
    $result = [];
    echo json_encode(searchId($database, $data), JSON_THROW_ON_ERROR);
}

function searchId(object $conn, object $short){
    $sql = 'SELECT * FROM `short_code` where `short_code` = :code';
    $params = [ 'code' => $short->value ];
    $users = $conn->query($sql, $params);
    return $users[0]['id_client'];
}
