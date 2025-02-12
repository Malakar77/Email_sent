<?php

require_once __DIR__ . '/../Migration.php';

class CreateShortCodeTable extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE `short_code` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_client` int DEFAULT NULL,
            `short_code` varchar(255) DEFAULT NULL,
            `link` varchar(255) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `short_code`;";
        $this->pdo->exec($sql);
    }
}