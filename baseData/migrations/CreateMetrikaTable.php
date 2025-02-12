<?php


require_once __DIR__ . '/../Migration.php';

class CreateMetrikaTable extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE `metrika` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_client` int NOT NULL,
            `type` varchar(255) NOT NULL,
            `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `metrika`;";
        $this->pdo->exec($sql);
    }
}