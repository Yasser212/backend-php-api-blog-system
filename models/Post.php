<?php
namespace App\Models;



error_reporting(E_ALL);
ini_set('display_error', 1);

class Post
{
    public $id;
    public $category_id;
    public $title;
    public $description;
    public $created_at;

    private $connection;
    private $table = 'posts';

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function readPosts()
    {
        $query = 'SELECT categories.name as category, posts.id, posts.title, posts.description, posts.category_id, posts.created_at FROM ' . $this->table . ' posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY posts.created_at';

        $post = $this->connection->prepare($query);

        $post->execute();

        return $post;
    }

    public function read_single_Post($id)
    {
        $this->id = $id;

        $query = 'SELECT 
        categories.name as category,
        posts.id,
        posts.title,
        posts.description,
        posts.category_id,
        posts.created_at
        FROM ' . $this->table . ' posts LEFT JOIN categories 
        ON posts.category_id = categories.id 
        WHERE posts.id=:id
        LIMIT 0,1';

        $post = $this->connection->prepare($query);

        $post->bindValue('id', $this->id, PDO::PARAM_INT);
        $post->execute();

        return $post;
    }

    // public function create_new_post($params)
    // {
    //     try {

    //     }
    //     catch (PDOException $e) {
    //         echo $e->getMessage();
    //     }
    // }

}