<?php

namespace App\Repositories\Files;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface 
{
    public function getAllPosts() 
    {
        return Post::all();
    }

    public function getPostById($postId) 
    {
        return Post::findOrFail($postId);
    }

    public function deletePost($postId) 
    {
        Post::destroy($postId);
    }

    public function createPost(array $postDetails) 
    {
        return Post::create($postDetails);
    }

    public function updatePost($postId, array $newDetails) 
    {
        return Post::whereId($postId)->update($newDetails);
    }
}