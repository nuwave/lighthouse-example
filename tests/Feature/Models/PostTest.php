<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePost(): void
    {
        $this->be(factory(User::class)->create());

        $id = Post::max('id') + 1;
        $title = 'Some title';
        $content = 'Some content';

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
                'title' => $title,
                'content' => $content,
            ])->assertExactJson([
                'data' => [
                    'createPost' => [
                        'id' => "$id",
                        'title' => $title,
                        'content' => $content,
                    ]
                ]
            ]);
    }
}
