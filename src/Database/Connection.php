<?php 

namespace App\Database;

use PDO;
use PDOException;

class Connection {
    private $host = '127.0.0.1';
    private $db = 'producer-portfolio';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    public function connect() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFSULT_FETCH_MODE    => PDO::FETCH_ASSOC, 
            PDO::ATTR_EMULATE_PREPARES      => false
        ];

        try {
            return new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Attempt to connect to database failed: " . $e->getMessage());
        }
    }
}