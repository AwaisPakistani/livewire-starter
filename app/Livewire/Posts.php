<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PostRepositoryInterface;

class Posts extends Component
{
    public $posts, $title, $content, $post_id;
    public $isOpen = false;
    
    protected $postRepository;

    public function mount(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function render()
    {
        if (!$this->postRepository) {
            $postRepository = app(PostRepositoryInterface::class);
            $this->postRepository = $postRepository;
        }
        $this->posts = $this->postRepository->getAllPosts();

        return view('livewire.posts');
    }

    public function create()
    {
        $this->resetInputFields();
        // dd('kksldf');
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
        $postRepository = app(PostRepositoryInterface::class);

        // dd('store');
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $data = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        if($this->post_id) {
            $postRepository->updatePost($this->post_id, $data);
            // Post::whereId($this->post_id)->update($data);
            session()->flash('message', 'Post Updated Successfully.');
        } else {
            // Post::create($data);
            $postRepository->createPost($data);
            session()->flash('message', 'Post Created Successfully.');
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $postRepository = app(PostRepositoryInterface::class);
        // dd('edit');
        $post = $postRepository->getPostById($id);
        // $post = Post::whereId($id)->first();
        $this->post_id = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        
        // $this->resetInputFields();
        $this->openModal();
    }

    public function delete($id)
    {   
        $postRepository = app(PostRepositoryInterface::class);
        // dd($postRepository);
        $postRepository->deletePost($id);
        // Post::destroy($id);
        session()->flash('message', 'Post Deleted Successfully.');
    }
}