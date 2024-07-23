<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Permission
{
    private $db;
    public $id;
    public $name;

    public function __construct($data = [])
    {
        $this->db = (new Database())->connect();
        if (!empty($data)) {
            $this->name = $data['name'];
        }
    }

    public static function all($db)
    {
        $query = $db->query("SELECT * FROM permissions");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($db, $id)
    {
        $query = $db->prepare("SELECT * FROM permissions WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $query = $this->db->prepare("INSERT INTO permissions (name) VALUES (:name)");
        return $query->execute(['name' => $this->name]);
    }
}