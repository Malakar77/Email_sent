# Email Sent

Этот проект предназначен для отправки email-сообщений.

## Установка и настройка

1. Клонируйте репозиторий:
   ```sh
   git clone https://github.com/Malakar77/Email_sent.git
   ```
2. Перейдите в директорию проекта:
   ```sh
   cd Email_sent
   ```
3. Установите зависимости:
   ```sh
   composer install
   ```
4. Скопируйте файл `.env.example` и переименуйте его в `.env`:
   ```sh
   cp .env.example .env
   ```
5. Настройте параметры почты в `.env` файле:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.example.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@example.com
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@example.com
   MAIL_FROM_NAME="Your Name"
   LINK_FILE=/path/index.html # Верстка рассылки
   ALT_BODY=/альтернативный текст
   ```

6. Запустите рассылки:
   ```sh
   php Sent.php
   ```

## Поддержка

Если у вас возникли вопросы или проблемы, создайте issue в репозитории или свяжитесь со мной.

## Лицензия

Этот проект распространяется под лицензией MIT.

