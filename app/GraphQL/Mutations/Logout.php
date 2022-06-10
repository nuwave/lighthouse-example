<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    public function __invoke(): ?User
    {
        $guardConfig = config('sanctum.guard');
        assert(is_array($guardConfig));

        $guardName = Arr::first($guardConfig);
        assert(is_string($guardName));

        $guard = Auth::guard($guardName);

        $user = $guard->user();

        $guard->logout();

        return $user;
    }
}
