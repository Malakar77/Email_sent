<?php
namespace DataBaseMysql;

require __DIR__.'/../../vendor/autoload.php';
use PDO;
use PDOException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__. '/../../');
$dotenv->load();

class DataBaseMysql {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {

        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'my_database';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function getConnection(): ?PDO {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("Ошибка соединения с БД: " . $exception->getMessage(), 3, 'error_log.txt');
            echo "Ошибка соединения с БД: " . $exception->getMessage();  // Output the detailed error message
        }
        return $this->conn;
    }


    public function query($sql, $params = [])
    {
        $sth = $this->conn->prepare($sql);

        if (!$sth) {
            throw new PDOException('Ошибка подготовки запроса: ' . implode(" ", $this->conn->errorInfo()));
        }

        $sth->execute($params);

        if ($sth->errorCode() !== '00000') {
            throw new PDOException('Ошибка выполнения запроса: ' . implode(" ", $sth->errorInfo()));
        }

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isConnected(): bool {
        return isset($this->conn) && $this->conn instanceof PDO;
    }
}
