<?php
namespace App\Controllers;

use App\Models\User;
use App\Config\Database;

class UserController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function getUsers()
    {
        $result = $this->user->read();
        $num = $result->rowCount();

        if ($num > 0) {
            $users_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    'id' => $id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email
                );
                array_push($users_arr, $user_item);
            }
            return json_encode($users_arr);
        } else {
            return json_encode(array('message' => 'No Users Found'));
        }
    }

    public function getUser($id)
    {
        $this->user->id = $id;
        $this->user->read_single();
        $this->user->get_roles();

        $user_arr = array(
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'roles' => $this->user->roles
        );

        return json_encode($user_arr);
    }
}