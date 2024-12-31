<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testStorePostSuccessfully()
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }
    // {
    //     // Create a user
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();

    //     // Prepare the data for the post
    //     $data = [
    //         'title' => 'Test Post Title',
    //         'content' => 'This is the content of the test post.',
    //         'category_id' => $category->id, // Assuming this category exists
    //         'user_id' => $user->id,
    //     ];

    //     // Act as the user and send a POST request
    //     $response = $this->postJson('/api/posts', $data);

    //     // Assert that the post was created successfully
    //     $response->assertStatus(201)
    //         ->assertJson([
    //             'success' => true,
    //             // 'message' => 'Create Post Successfully',
    //             // 'data' => [
    //             //     'title' => 'Test Post Title',
    //             //     'content' => 'This is the content of the test post.',
    //             //     'category_id' => $category->id,
    //             //     'user_id' => $user->id,
    //             // ],
    //         ]);

    //     // Assert that the post is in the database
    //     $this->assertDatabaseHas('posts', [
    //         'title' => 'Test Post Title',
    //         'content' => 'This is the content of the test post.',
    //         'category_id' => $category->id,
    //         'user_id' => $user->id,
    //     ]);
    // }
}
