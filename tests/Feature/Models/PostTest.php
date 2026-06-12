<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post(): void
    {
        $user = User::factory()->createOne();
        $this->be($user);

        $this
            ->graphQL(/** @lang GraphQL */ '
            mutation ($title: String!, $content: String!) {
                createPost(input: {
                    title: $title
                    content: $content
                }) {
                    id
                    title
                    content
                }
            }
            ', [
                'title' => 'Some title',
                'content' => 'Some content',
            ])
            ->assertExactJson([
                'data' => [
                    'createPost' => [
                        'id' => '1',
                        'title' => 'Some title',
                        'content' => 'Some content',
                    ]
                ]
            ]);
    }
}
