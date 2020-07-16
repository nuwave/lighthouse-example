<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Logout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args): ?User
    {
        $guard = Auth::guard(config('sanctum.guard', 'web'));

        /** @var \App\Models\User|null $user */
        $user = $guard->user();
        $guard->logout();

        return $user;
    }
}
