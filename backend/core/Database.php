<?php

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $configPath = __DIR__ . '/../config/config.xml';

        if (!file_exists($configPath)) {
            throw new Exception("File database.xml tidak ditemukan");
        }

        $xml = simplexml_load_file($configPath);

        if ($xml === false) {
            throw new Exception("Gagal membaca database.xml");
        }

        $dbConfig = $xml->database;

        $host     = (string) $dbConfig->host;
        $dbname   = (string) $dbConfig->name;
        $username = (string) $dbConfig->username;
        $password = (string) $dbConfig->password;

        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (PDOException $e) {
            die(json_encode([
                'success' => false,
                'message' => 'Koneksi database gagal',
                'error' => $e->getMessage()
            ]));
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
