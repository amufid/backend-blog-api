<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Attachment;
use App\Models\Post;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category', 'attachment'])->get();
        return response()->json([
            'success' => true,
            'message' => 'List of Posts',
            'data' => PostResource::collection($posts),
        ], 200);
    }

    public function getLastestData()
    {
        $posts = Post::orderBy('created_at', 'desc')->with(['category', 'attachment'])->take(3)->get();
        return response()->json([
            'success' => true,
            'message' => 'List of Posts',
            'data' => $posts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = ValidationHelper::validateDataPost($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Create Post Successfully',
            'data' => $post,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        // $post = Post::with(['category', 'attachment'])->find($slug);
        $post = Post::with(['category', 'attachment'])->where('title', $slug)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Post',
            'data' => PostResource::make($post),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $errors = ValidationHelper::validateDataPost($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Update Post Successfully',
            'data' => PostResource::make($post),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        $attachments = Attachment::where('post_id', $id)->get();

        try {
            // Iterasi untuk menghapus file di Cloudinary
            foreach ($attachments as $attachment) {
                if ($attachment->public_id) {
                    Cloudinary::destroy($attachment->public_id);
                }
            }

            Attachment::where('post_id', $id)->delete();
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post or attachments. Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
