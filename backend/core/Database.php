<?php
class Database {
    private $pdo;

    public function connect() {
        if ($this->pdo == null) {
            $config = simplexml_load_file(__DIR__ . "/../config/config.xml");

            $host = $config->database->host;
            $db = $config->database->name;
            $user = $config->database->user;
            $pass = $config->database->password;

            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",$user,$pass
            );
        }
        return $this->pdo;
    }
}