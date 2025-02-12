<?php
require_once __DIR__.'/../vendor/autoload.php';

use DataBaseMysql\DataBaseMysql;

$database = new DataBaseMysql();
$conn = $database->getConnection();
