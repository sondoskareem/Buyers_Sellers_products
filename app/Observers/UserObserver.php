<?php


namespace App\Observers;

use App\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        // $user->api_token = Str::random(60);
    }

}