<?php
global $database;
require 'vendor/autoload.php';
require 'baseData/database.php';

use Mailer\MailSender;
use TerminalProgress\Bar;

// Делаем запрос к базе данных
$sql = "SELECT id, name, email FROM mailing WHERE `deleted_at` IS NULL;";
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

    $id = trim($user['id']);
    $email = trim($user['email']);
    $name = trim($user['name']);
    $short = [
        'telegram' => generateShortCode(),
        'website' => generateShortCode(),
        'unsub' => generateShortCode(),
    ];

    $link = [
        'website' => $_ENV['DOMAIN_APP'].'/metrika/index.php?s='.$short['website'],
        'telegram' => $_ENV['DOMAIN_APP'].'/metrika/index.php?s='.$short['telegram'],
        'unsub' => $_ENV['DOMAIN_APP'].'/metrika/unsub/index.html?us='.$short['unsub'],
    ];

    $linkOrig = [
        'website' => $_ENV['DOMAIN_APP'],
        'telegram' => $_ENV['TELEGRAM_GROUP'],
        'unsubscribe' => $_ENV['DOMAIN_APP'].'/metrika/unsub/index.html?us='.$short['unsub'],
    ];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Некорректный email " . $email, 3, 'error_log.log');
        echo 'Некорректный email ' . $email . "\n";
        continue;
    }

    $sql = 'INSERT INTO `short_code` (`id`, `id_client`, `short_code`, `link`, `created_at`, `updated_at`)
            VALUES (NULL, :id_client, :short_code, :link, now(), now());';

    $paramWebSite = [
        'id_client' => $id,
        'short_code' => $short['website'],
        'link' => $linkOrig['website'],
    ];

    $paramTelegram = [
        'id_client' => $id,
        'short_code' => $short['telegram'],
        'link' => $linkOrig['telegram'],
    ];

    $paramDelete = [
        'id_client' => $id,
        'short_code' => $short['unsub'],
        'link' => $linkOrig['unsubscribe'],
    ];

    $database->query($sql, $paramWebSite);
    $database->query($sql, $paramTelegram);
    $database->query($sql, $paramDelete);


    $sentEmails = [];
    // Отправляем письмо
    if ($mailer->sendMail($email, $name, $link, $sentEmails)) {
        $pg->interupt("Письмо успешно отправлено! " . htmlspecialchars_decode($name) . " " . $email);
    } else {
        $pg->interupt("Письмо не отправлено! " . htmlspecialchars_decode($name) . " " . $email);
    }


    usleep(100000);
    $pg->tick();
}

function generateShortCode($length = 6): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $shortCode = '';
    for ($i = 0; $i < $length; $i++) {
        $shortCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $shortCode;
}

