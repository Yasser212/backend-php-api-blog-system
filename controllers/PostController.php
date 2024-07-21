<?php


// namespace App\controllers\PostController;



error_reporting(E_ALL);
ini_set('display_error', 1);

// Header('Access-Control-Allow-Origin: *');
// Header('Content-Type: application/json');
// Header('Access-Control-Allow-Method: POST');

// use App\Models\Post\Post;
// use config\database\Database;

include_once '../../App/models/Post.php';
include_once '../../config/Database.php';

$database = new Database;
$db = $database->connect();

$post = new Post($db);

$data = $post->readPosts();

if ($data->rowCount()) {
    $post = [];

    while ($row = $data->fetch(PDO::FETCH_OBJ)) {
        // $post[$row->id] = [
        $post[] = [
            'id' => $row->id,
            'categoryName' => $row->category,
            'description' => $row->description,
            'title' => $row->title,
            'created_at' => $row->created_at
        ];
    }
    echo json_encode($post);
} else {
    echo json_encode(['message' => 'No posts found']);
}