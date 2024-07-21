<?php
require '../vendor/autoload.php';

use App\Controllers\UserController;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            echo $userController->getUser($id);
        } else {
            echo $userController->getUsers();
        }
        break;

    // Add other cases for POST, PUT, DELETE as needed
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}