<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $post_id)
    {
        $attachments = Attachment::where('post_id', $post_id)->get();
        return [
            'success' => true,
            'message' => 'List of Attachments',
            'data' => AttachmentResource::collection($attachments),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = ValidationHelper::validateDataAttachment($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $cloudinaryImage = $image->storeOnCloudinary('attachments');
                $imageUrl = $cloudinaryImage->getSecurePath();

                $attachment = Attachment::create([
                    'post_id' => $request->post_id,
                    'image' => $imageUrl,
                    'public_id' => $cloudinaryImage->getPublicId(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Create Attachment Successfully',
                    'data' => AttachmentResource::make($attachment),
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image to Cloudinary.',
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get Attachment Successfully',
            'data' => AttachmentResource::make($attachment),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $errors = ValidationHelper::validateDataAttachment($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }

        $attachment = Attachment::find($id);

        if (!$attachment) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found',
            ], 404);
        }

        if ($request->hasFile('image')) {
            try {
                // Hapus gambar lama dari Cloudinary
                $deleteResult = Cloudinary::destroy($attachment->public_id);
                if (!$deleteResult) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to delete the old image from Cloudinary.',
                    ], 500);
                }

                // Unggah gambar baru
                $image = $request->file('image');
                $cloudinaryImage = $image->storeOnCloudinary('attachments');
                $imageUrl = $cloudinaryImage->getSecurePath();

                // Update attachment
                $attachment->update([
                    'post_id' => $request->post_id,
                    'image' => $imageUrl,
                    'public_id' => $cloudinaryImage->getPublicId(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Update Attachment Successfully',
                    'data' => AttachmentResource::make($attachment),
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image to Cloudinary: ' . $e->getMessage(),
                ], 500);
            }
        } else {
            // Update hanya post_id jika tidak ada gambar
            $attachment->update([
                'post_id' => $request->post_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attachment updated successfully (without new image)',
                'data' => AttachmentResource::make($attachment),
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found',
            ], 404);
        }

        try {
            Cloudinary::destroy($attachment->public_id);
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delete Attachment Successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image from Cloudinary.',
            ], 500);
        }
    }
}
