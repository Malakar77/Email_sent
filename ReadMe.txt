Email Sent

Этот проект предназначен для отправки email-сообщений.

Установка и настройка

Клонируйте репозиторий:

git clone https://github.com/Malakar77/Email_sent.git

Перейдите в директорию проекта:

cd Email_sent

Установите зависимости:

composer install

Скопируйте файл .env.example и переименуйте его в .env:

cp .env.example .env

Настройте параметры почты в .env файле:

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="Your Name"
LINK_FILE=/path/index.html верстка рассылки
ALT_BODY= /альтернативный текст

Запустите Рассылки:
php Sent.php

Поддержка

Если у вас возникли вопросы или проблемы, создайте issue в репозитории или свяжитесь со мной.

Лицензия

Этот проект распространяется под лицензией MIT.
