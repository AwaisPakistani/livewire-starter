<?php 
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginViewResponse;
use Illuminate\Contracts\Support\Responsable;

class CustomLoginViewResponse implements LoginViewResponse
{
    public function toResponse($request)
    {
        dd('resp');
        return view('livewire.auth.login'); // or wherever your login view is
    }
}
