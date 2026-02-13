<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Error\Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{email: string, password: string}  $args
     */
    public function __invoke($_, array $args): User
    {
        $guardConfig = config('sanctum.guard');
        assert(is_array($guardConfig));

        $guardName = Arr::first($guardConfig);
        assert(is_string($guardName));

        $guard = Auth::guard($guardName);

        if( ! $guard->attempt($args)) {
            throw new Error('Invalid credentials.');
        }

        $user = $guard->user();
        assert($user instanceof User, 'must receive User after successful login');

        return $user;
    }
}
