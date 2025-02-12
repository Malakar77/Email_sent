<?php

abstract class Migration
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract public function up(); // Метод для применения миграции

    abstract public function down(); // Метод для отката миграции
}