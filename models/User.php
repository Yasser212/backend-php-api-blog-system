<?php
namespace App\Models;

use PDO;

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password_hash;
    public $roles = [];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->password_hash = $row['password_hash'];
    }

    public function get_roles()
    {
        $query = 'SELECT r.id, r.name FROM roles r INNER JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $this->roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function has_permission($permission_name)
    {
        foreach ($this->roles as $role) {
            $query = 'SELECT p.name FROM permissions p INNER JOIN role_permissions rp ON p.id = rp.permission_id WHERE rp.role_id = ? AND p.name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $role['id']);
            $stmt->bindParam(2, $permission_name);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }
}