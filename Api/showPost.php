<?php

// error_reporting(E_ALL);
// ini_set('display_error', 1);

// Header('Access-Control-Allow-Origin: *');
// Header('Content-Type: application/json');
// Header('Access-Control-Allow-Method: POST');


// include_once '../config/Database.php';
// include_once '../App/models/Post.php';

// $database = new Database;
// $db = $database->connect();

// $post = new Post($db);

// if (isset($_GET['id'])) {

//     $data = $post->read_single_Post($_GET['id']);

//     if ($post->rowCount()) {
//         $posts = [];

//         while ($row = $data->fetch(PDO::FETCH_OBJ)) {

//             $posts[$row->id] = [
//                 'id' => $row->id,
//                 'categoryName' => $row->category,
//                 'description' => $row->description,
//                 'title' => $row->title,
//                 'created_at' => $row->created_at
//             ];
//         }
//         echo json_encode($posts);
//     } else {
//         echo json_encode(['message' => 'No posts found']);
//     }
// }




// Enable error reporting for all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for CORS and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

// Include the necessary files
include_once '../config/Database.php';
include_once '../App/models/Post.php';

// Instantiate the Database and connect
$database = new Database();
$db = $database->connect();

// Instantiate a Post object
$post = new Post($db);

// Check if 'id' is set in the GET parameters
if (isset($_GET['id'])) {

    // Retrieve the post data
    $data = $post->read_single_post($_GET['id']);

    // Check if any rows were returned
    if ($data->rowCount() > 0) {
        $posts = [];

        // Fetch the post data
        while ($row = $data->fetch(PDO::FETCH_OBJ)) {
            $posts[$row->id] = [
                'id' => $row->id,
                'categoryName' => $row->category,
                'description' => $row->description,
                'title' => $row->title,
                'created_at' => $row->created_at
            ];
        }
        // Output the posts as JSON
        echo json_encode($posts);
    } else {
        // Output a message if no posts were found
        echo json_encode(['message' => 'No posts found']);
    }
} else {
    // Output a message if 'id' is not provided in the GET parameters
    echo json_encode(['message' => 'No ID provided']);
}