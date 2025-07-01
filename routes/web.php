<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Posts;

Route::get('/posts', Posts::class);

Route::get('/', function () {
    return view('welcome');
});
