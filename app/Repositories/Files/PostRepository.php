<?php

namespace App\Repositories\Files;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface 
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }
    public function getAllPosts() 
    {
        $posts = $this->model->all();
        return $posts;
    }

    public function getPostById($postId) 
    {
        return $this->model->findOrFail($postId);
    }

    public function deletePost($postId) 
    {
        $this->model->destroy($postId);
    }

    public function createPost(array $postDetails) 
    {
        return $this->model->create($postDetails);
    }

    public function updatePost($postId, array $newDetails) 
    {
        return $this->model->whereId($postId)->update($newDetails);
    }
}