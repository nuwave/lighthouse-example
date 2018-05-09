<?php

namespace App\Http\GraphQL\Mutations;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AccountMutator
{
    public function login($root, array $args, $context)
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
        throw_if(array_has($auth_token, 'error'), new \Exception($auth_token['error']));
        $user = $this->user($auth_token);

        return compact('auth_token', 'user');
    }

    /**
     * Get user from access token.
     *
     * @param  array  $auth_token
     * @return \App\User|null
     */
    protected function user(array $auth_token)
    {
        $jwt = array_get($auth_token, 'access_token');
        if (! $jwt) {
            return null;
        }

        $tks = explode('.', $jwt);
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $body = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        $sub = data_get($body, 'sub');

        if (! $sub) {
            return null;
        }

        return \App\User::find($sub);
    }
}
