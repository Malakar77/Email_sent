<?php

require_once __DIR__ . '/../Migration.php';

class CreateMailingTable extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE `mailing` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Оставляем это
    `name` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `create_at` TIMESTAMP NULL DEFAULT NULL,
    `update_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `mailing`;";
        $this->pdo->exec($sql);
    }
}