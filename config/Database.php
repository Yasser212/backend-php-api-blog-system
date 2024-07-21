<?php

//Database connection

namespace App\config;

use PDO;
use PDOException;

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "blogapp";
    private $conn;



    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbname,
                $this->username,
                $this->password
            );
        }
        catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}