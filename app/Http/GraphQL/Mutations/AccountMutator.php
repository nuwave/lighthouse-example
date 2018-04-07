<?php

namespace App\Http\GraphQL\Mutations;

use Illuminate\Http\Request;

class AccountMutator
{
    public function login($root, array $args)
    {
        $data = array_merge($args, [
            'grant_type' => 'password',
            'scope'      => '',
        ]);

        $request = Request::create('oauth/token', 'POST', $data, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);

        $response = app()->handle($request);
        $auth_token = json_decode($response->getContent(), true);
        $user = \App\User::where('email', $args['username'])->first();

        return compact('auth_token', 'user');
    }
}
