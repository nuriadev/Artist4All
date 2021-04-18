<?php
namespace Artist4all\Model;

class Database {
    protected static ?\Artist4all\Model\Database $instance = null;

    public static function getInstance(): \Artist4all\Model\Database {
        if (is_null(static::$instance)) { static::$instance = new \Artist4all\Model\Database(); }
        return static::$instance;
    }

    private \PDO $conn;

    protected function __construct() {
        $dsn = 'mysql:host=artist4all_db;dbname=artist4alldb';
        $dbusername = 'root';
        $dbpassword = 'password';
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_EMULATE_PREPARES => FALSE);
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    public function getConnection(): \PDO { return $this->conn; }
}
