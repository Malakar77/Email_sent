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
5. Настройте параметры почты и подключение к базе данных в `.env` файле:
   ```env
   #Подключение к Базе данных
   DB_HOST=localhost
   DB_NAME=my_sent_email
   DB_USER=user
   DB_PASS=
   # домен ваш
   DOMAIN_APP=
   #Телеграм группа
   TELEGRAM_GROUP=
   #Настройка почтовой отправки
   EMAIL_HOST=vip232.hosting.reg.ru
   EMAIL_SMTP=true;
   EMAIL_USERNAME=mail@example.ru
   EMAIL_PASS=my_pass
   EMAIL_SMTP_SECURE=PHPMailer::ENCRYPTION_STARTTLS
   EMAIL_PORT=587
   EMAIL_CHARSET=UTF-8
   #Настройка Сообщения HTML
   EMAIL_SUBJECT=mail #Тема письма
   LINK_FILE=/path/index.html # Верстка рассылки
   ALT_BODY='альтернативный текст'
   ```
6. Запустить миграцию 
    ```sh
   cd baseData
   php migrate.php
   ```
    Откат миграции
   В файле migrate.php раскоментируйте ```$migrator->rollback(); ```
   и закоментируйте строку  ```$migrator->migrate(); ```

   Запустите скрипт
   ```sh
   cd baseData
   php migrate.php
   ```
8. Добавить клиентов для рассылки в базу данных таблица `mailing`
9. Запустите рассылки:
   ```sh
   php Sent.php
   ```
## метрика переходов и отписка от рассылки
1. Выгрузить папку `sait` на хостинг `https:/yourDomain/metrika/
2. настроить фаил `.env`
   ```env
   #Подключение к Базе данных аналогично файлу .env основного проекта
   DB_HOST= 
   DB_NAME=
   DB_USER=
   DB_PASS=
   #ваш домен
   DOMAIN= 
   ```
## Поддержка

Если у вас возникли вопросы или проблемы, создайте issue в репозитории или свяжитесь со мной.

## Лицензия

Этот проект распространяется под лицензией MIT.

