<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_comment_get_route()
    {
        Comment::factory()->count(10)->create();

        $this->getJson(route('comments.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => []
            ]);
    }
}
