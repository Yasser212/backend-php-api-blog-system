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
    public $is_admin;
    public $roles = [];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fetch all users
    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fetch a single user by ID
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
        $this->is_admin = $row['is_admin'];
        $this->get_roles();
    }

    // Fetch user roles
    public function get_roles()
    {
        $query = 'SELECT r.id, r.name FROM roles r INNER JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $this->roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if user has a specific permission
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

    // Assign a role to the user
    public function assign_role($role_id)
    {
        $query = 'INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':role_id', $role_id);
        return $stmt->execute();
    }

    // Save a new user to the database
    public function save()
    {
        $query = 'INSERT INTO ' . $this->table . ' (first_name, last_name, email, password_hash, is_admin) VALUES (:first_name, :last_name, :email, :password_hash, :is_admin)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':is_admin', $this->is_admin);
        return $stmt->execute();
    }
}