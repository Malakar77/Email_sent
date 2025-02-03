<?php

use DataBaseMysql\DataBaseMysql;
use Mailer\MailSender;
use TerminalProgress\Bar;

require 'vendor/autoload.php';

// Создаем экземпляр класса Database
$database = new DataBaseMysql();
$conn = $database->getConnection();

// Получаем данные из .env
$table = $_ENV['TABLE'];
$column_name = $_ENV['COLUMN_MAME'];
$column_email = $_ENV['COLUMN_EMAIL'];

// Проверяем, что имена таблицы и колонок валидны
function validateName($name) {
    return preg_match('/^[a-zA-Z0-9_]+$/', $name);
}

if (!validateName($table) || !validateName($column_name) || !validateName($column_email)) {
    throw new Exception("Неверное имя таблицы или колонки.");
}

// Делаем запрос к базе данных
$sql = "SELECT " . $column_name . ", " . $column_email . " FROM " . $table;
$users = $database->query($sql);

echo " 
 _____                 _ _   ____             _   
| ____|_ __ ___   __ _(_) | / ___|  ___ _ __ | |_ 
|  _| | '_ ` _ \ / _` | | | \___ \ / _ \ '_ \| __|
| |___| | | | | | (_| | | |  ___) |  __/ | | | |_ 
|_____|_| |_| |_|\__,_|_|_| |____/ \___|_| |_|\__|

\r\n";

// Создаем объект MailSender для отправки писем
$mailer = new MailSender();

// Используем прогресс-бар
$pg = new Bar(count($users), "Выполнено: [:bar] - :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");

foreach ($users as $user) {
    $email = trim($user['email']);
    $name = trim($user['name']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         error_log("Некорректный email " . $email, 3, 'error_log.txt');
        echo 'Некорректный email ' . $email . "\n";
        continue;
    }

    // Отправляем письмо
    if ($mailer->sendMail($email, $name)) {
        $pg->interupt("Письмо успешно отправлено! " . htmlspecialchars_decode($name) . " " . $email);
    }

    usleep(100000);
    $pg->tick();
}

