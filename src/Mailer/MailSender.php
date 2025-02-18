<?php

namespace Mailer;
require __DIR__. '/../../vendor/autoload.php';
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailSender {
    private $mail;
    private $key;

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

    public function sendMail(string $email, string $name, array $link, array &$sentEmails): bool
    {
        // Проверяем, был ли уже отправлен email
        if (in_array($email, $sentEmails, true)) {
            return false; // Не отправляем письмо, если этот email уже есть
        }

        try {
            // Очищаем адреса перед добавлением нового
            $this->mail->clearAddresses(); // Очистка получателей
            $this->mail->clearCCs();      // Очистка CC
            $this->mail->clearBCCs();     // Очистка BCC
            $this->mail->clearAttachments(); // Очистка вложений

            // Устанавливаем получателя
            $this->mail->addAddress($email, htmlspecialchars_decode($name));

            // Тема письма
            $this->mail->Subject = $_ENV['EMAIL_SUBJECT'];

            // HTML-контент письма
            $this->mail->isHTML(true);
            $bodyContent = file_get_contents($_ENV['LINK_FILE']); // Загружаем HTML из файла
            $bodyContent = str_replace('{TELEGRAM}', $link['telegram'], $bodyContent);
            $bodyContent = str_replace('{WEBSITE}', $link['website'], $bodyContent);
            $bodyContent = str_replace('{USUB}', $link['unsub'], $bodyContent);
            $this->mail->Body = $bodyContent;
            $this->mail->AltBody = $_ENV['ALT_BODY'];

            // Отправка письма
            if ($this->mail->send()) {
                // Добавляем email в массив отправленных
                $sentEmails[] = $email;
                return true;
            }

            error_log("Не удалось отправить письмо: " . $this->mail->ErrorInfo, 3, 'error_log.log');
            return false;
        } catch (Exception $e) {
            error_log("Ошибка при отправке письма: " . $e->getMessage(), 3, 'error_log.log');
            return false;
        }
    }



}
