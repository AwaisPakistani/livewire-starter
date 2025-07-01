
<div>
    <div class="container" style="background-color:green;">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h2>Posts</h2>
                <button wire:click="create()" class="btn btn-primary">Create New Post</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->content }}</td>
                            <td>
                                <button wire:click="edit({{ $post->id }})" class="btn btn-primary">Edit</button>
                                <button wire:click="delete({{ $post->id }})" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        @if($isOpen)
        <div class="modal" style="display: block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $post_id ? 'Edit Post' : 'Create Post' }}</h5>
                        <button wire:click="closeModal()" type="button" class="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input wire:model="title" type="text" class="form-control" id="title">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea wire:model="content" class="form-control" id="content" rows="3"></textarea>
                                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeModal()" type="button" class="btn btn-secondary">Close</button>
                        <button wire:click.prevent="store()" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>