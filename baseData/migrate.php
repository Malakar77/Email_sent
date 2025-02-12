<?php
global $conn;
require __DIR__ . '/../vendor/autoload.php';
require_once 'Migrator.php';
require_once 'database.php';
//use DataBaseMysql\DataBaseMysql;
//
//$database = new DataBaseMysql();
//$conn = $database->getConnection();

$migrator = new Migrator($conn);

// Применить миграции
$migrator->migrate();

// Откатить последнюю миграцию
// $migrator->rollback();