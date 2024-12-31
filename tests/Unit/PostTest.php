<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    use RefreshDatabase;

    public function it_can_create_a_post()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'category_id' => 1,
            'user_id' => 1,
        ];

        $post = Post::create($data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('This is a test post', $post->content);
    }
}
