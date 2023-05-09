<?php

class Connection {
    private static $instance = null;
    private $connection;

    private function __construct($config) {
        try {
            $this->connection = new PDO("mysql:host={$config['db']['server']};dbname={$config['db']['dbname']}",
                $config['db']['dbuser'], $config['db']['dbpass']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getInstance($config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}