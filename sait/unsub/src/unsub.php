<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../../vendor/autoload.php';
use DataBaseMysql\DataBaseMysql;

$database = new DataBaseMysql();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Декодируем JSON-данные из тела запроса
    $data = json_decode(file_get_contents("php://input"), false);

    // Проверяем, удалось ли декодировать JSON
    if ($data === null) {
        http_response_code(400); // Неверный запрос
        echo json_encode(['success' => false, 'message' => 'Некорректный JSON']);
        exit;
    }

    // Проверяем, есть ли необходимые данные
    if (!isset($data->value)) {
        http_response_code(400); // Неверный запрос
        echo json_encode(['success' => false, 'message' => 'Отсутствует поле value']);
        exit;
    }

    // Подготавливаем SQL-запрос
    $sql = 'UPDATE mailing SET deleted_at = NOW() WHERE id = :id';
    $params = ['id' => $data->value];

    try {
        // Выполняем запрос
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        // Проверяем, была ли обновлена хотя бы одна строка
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Запись успешно обновлена']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Запись с указанным ID не найдена']);
        }
    } catch (PDOException $e) {
        // Обрабатываем ошибки базы данных
        http_response_code(500); // Ошибка сервера
        echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
    }
} else {
    // Если метод запроса не POST
    http_response_code(405); // Метод не поддерживается
    echo json_encode(['success' => false, 'message' => 'Используйте метод POST']);
}