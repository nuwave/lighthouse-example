<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_logout(): void
    {
        $user = User::factory()->createOne();
        $id = (string) $user->id;

        $this
            ->graphQL(/** @lang GraphQL */ '
            mutation ($email: String!) {
                login(email: $email, password: "password") {
                    id
                }
            }
            ', [
                'email' => $user->email,
            ])
            ->assertExactJson(['data' => ['login' => ['id' => $id]]]);

        $this
            ->graphQL(/** @lang GraphQL */ '{ me { id } }')
            ->assertExactJson(['data' => ['me' => ['id' => $id]]]);

        $this
            ->graphQL(/** @lang GraphQL */ 'mutation { logout { id } }')
            ->assertExactJson(['data' => ['logout' => ['id' => $id]]]);

        $this
            ->graphQL(/** @lang GraphQL */ '{ me { id } }')
            ->assertExactJson(['data' => ['me' => null]]);
    }

    public function test_login_with_wrong_password(): void
    {
        $user = User::factory()->createOne();

        $this
            ->graphQL(/** @lang GraphQL */ '
            mutation ($email: String!) {
                login(email: $email, password: "wrong") {
                    id
                }
            }
            ', [
                'email' => $user->email,
            ])
            ->assertGraphQLErrorMessage('Invalid credentials.');
    }
}
