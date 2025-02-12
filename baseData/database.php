<?php
require __DIR__ . '/../vendor/autoload.php';
require_once 'Migrator.php';

use DataBaseMysql\DataBaseMysql;

$database = new DataBaseMysql();
$conn = $database->getConnection();