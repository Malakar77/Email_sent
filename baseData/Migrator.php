<?php

class Migrator
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Применяет все миграции.
     */
    public function migrate()
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();

        $newMigrations = [];

        foreach ($migrationFiles as $migrationFile) {
            $migrationClassName = pathinfo($migrationFile, PATHINFO_FILENAME);

            if (!in_array($migrationClassName, $appliedMigrations)) {
                require_once $migrationFile;

                // Создаём экземпляр класса миграции
                $migration = new $migrationClassName($this->pdo);

                // Применяем миграцию
                echo "Применение миграции: $migrationClassName" . PHP_EOL;
                $migration->up();

                // Сохраняем информацию о применённой миграции
                $newMigrations[] = $migrationClassName;
            }
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
            echo "Все миграции успешно применены." . PHP_EOL;
        } else {
            echo "Новых миграций для применения нет." . PHP_EOL;
        }
    }

    /**
     * Создаёт таблицу migrations, если она не существует.
     */
    protected function createMigrationsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `migrations` (
              `id` int NOT NULL AUTO_INCREMENT,
              `migration` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    /**
     * Возвращает список применённых миграций.
     */
    protected function getAppliedMigrations()
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Откатывает последнюю миграцию.
     */
    public function rollback(): void
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();

        if (empty($appliedMigrations)) {
            echo "Нет применённых миграций для отката." . PHP_EOL;
            return;
        }

        $lastMigration = end($appliedMigrations);
        require_once "migrations/$lastMigration.php";

        $migration = new $lastMigration($this->pdo);
        $migration->down();

        $this->removeMigration($lastMigration);
        echo "Миграция откачена: $lastMigration" . PHP_EOL;
    }

    /**
     * Возвращает список файлов миграций.
     */
    protected function getMigrationFiles(): false|array
    {
        return glob(__DIR__ . '/migrations/*.php');
    }

    /**
     * Сохраняет информацию о применённых миграциях.
     */
    protected function saveMigrations(array $migrations)
    {
        $values = implode(',', array_map(fn($migration) => "('$migration')", $migrations));
        $this->pdo->exec("INSERT INTO migrations (migration) VALUES $values");
    }

    /**
     * Удаляет информацию о миграции после отката.
     */
    protected function removeMigration(string $migration)
    {
        $this->pdo->exec("DELETE FROM migrations WHERE migration = '$migration'");
    }
}