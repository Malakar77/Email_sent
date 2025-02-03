<?php

namespace Mailer;
require __DIR__. '/../../vendor/autoload.php';
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailSender {
    private $mail;

    /**
     * @throws Exception
     */
    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__. '/../../');
        $dotenv->load();

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['EMAIL_HOST'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['EMAIL_USERNAME'];
        $this->mail->Password   = $_ENV['EMAIL_PASS'];
        $this->mail->SMTPSecure = $_ENV['EMAIL_SMTP_SECURE'];
        $this->mail->Port       = $_ENV['EMAIL_PORT'];
        $this->mail->CharSet = $_ENV['EMAIL_CHARSET'];
        $this->mail->setFrom($_ENV['EMAIL_USERNAME'], 'АК Сплав');
    }

    public function sendMail($email, $name): bool
    {
        try {
            // Устанавливаем получателя
            $this->mail->addAddress($email, htmlspecialchars_decode($name));

            // Тема письма
            $this->mail->Subject =  $_ENV['EMAIL_SUBJECT'];

            // HTML-контент письма
            $this->mail->isHTML(true);
            $this->mail->Body    = file_get_contents($_ENV['LINK_FILE']); // Загружаем HTML из файла
            $this->mail->AltBody = $_ENV['ALT_BODY'];

            // Отправка письма
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo "Ошибка при отправке письма: {$this->mail->ErrorInfo}";
            return false;
        }
    }
}
