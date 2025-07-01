<?php

namespace App\Livewire;

use Livewire\Component;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post;
class Posts extends Component
{
    public $posts = [], $title, $content, $post_id;
    public $isOpen = false;
    
    private $postRepository;

     public function mount(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function render()
    {
        // dd(Post::all());
        $this->posts = $this->postRepository->getAllPosts();
        \Log::debug('Posts data:', $this->posts->toArray());
        // dd($this->posts);
        return view('livewire.posts');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->post_id = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $data = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        if($this->post_id) {
            $this->postRepository->updatePost($this->post_id, $data);
            session()->flash('message', 'Post Updated Successfully.');
        } else {
            $this->postRepository->createPost($data);
            session()->flash('message', 'Post Created Successfully.');
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $post = $this->postRepository->getPostById($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        
        $this->openModal();
    }

    public function delete($id)
    {
        $this->postRepository->deletePost($id);
        session()->flash('message', 'Post Deleted Successfully.');
    }
}