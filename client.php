<?php
use DataBaseMysql\DataBaseMysql;
require 'vendor/autoload.php'; // Подключаем PHPMailer

// Создаем экземпляр класса Database
$database = new DataBaseMysql();
// Получаем соединение с базой данных
$conn = $database->getConnection();
$users = $database->query("SELECT name_company, email_company FROM sale_company");

foreach ($users as $user) {
    $email = trim($user['email_company']); // Убираем пробелы и невидимые символы
    $name = trim($user['name_company']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Некорректный email ' . $email . "\n";
        continue;
    }

    $sql = 'INSERT INTO `mailing` (`id`, `name`, `email`, `create_at`, `update_at`)
            VALUES (NULL, :name, :email, NOW(), NOW())';

    $param = [];
    $param = [
        ":name" => $name,
        ":email" => $email,
    ];
    $database->query($sql, $param);
    echo 'Записан Email ' . htmlspecialchars_decode($name) ." ". $email . "\n";
}