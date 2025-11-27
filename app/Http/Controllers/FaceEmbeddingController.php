<?php

namespace App\Http\Controllers;

use App\Models\FaceEmbedding;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFaceEmbeddingRequest;
use App\Http\Requests\UpdateFaceEmbeddingRequest;
use App\Http\Resources\FaceEmbeddingResource;
use Illuminate\Support\Facades\Storage;

class FaceEmbeddingController extends Controller
{
    /**
     * Display a listing of the face embeddings.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $faceEmbeddings = FaceEmbedding::paginate(15);
        return FaceEmbeddingResource::collection($faceEmbeddings);
    }

    /**
     * Store a newly created face embedding in storage.
     *
     * @param  \App\Http\Requests\StoreFaceEmbeddingRequest  $request
     * @return \App\Http\Resources\FaceEmbeddingResource
     */
    public function store(StoreFaceEmbeddingRequest $request)
    {
        $faceEmbedding = FaceEmbedding::create($request->validated());
        return new FaceEmbeddingResource($faceEmbedding);
    }

    /**
     * Display the specified face embedding.
     *
     * @param  \App\Models\FaceEmbedding  $faceEmbedding
     * @return \App\Http\Resources\FaceEmbeddingResource
     */
    public function show(FaceEmbedding $faceEmbedding)
    {
        return new FaceEmbeddingResource($faceEmbedding);
    }

    /**
     * Update the specified face embedding in storage.
     *
     * @param  \App\Http\Requests\UpdateFaceEmbeddingRequest  $request
     * @param  \App\Models\FaceEmbedding  $faceEmbedding
     * @return \App\Http\Resources\FaceEmbeddingResource
     */
    public function update(UpdateFaceEmbeddingRequest $request, FaceEmbedding $faceEmbedding)
    {
        $faceEmbedding->update($request->validated());
        return new FaceEmbeddingResource($faceEmbedding);
    }

    /**
     * Remove the specified face embedding from storage.
     *
     * @param  \App\Models\FaceEmbedding  $faceEmbedding
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FaceEmbedding $faceEmbedding)
    {
        $faceEmbedding->delete();
        return response()->json(['message' => 'Face embedding deleted successfully']);
    }

    /**
     * Upload face photo for AI training.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        $path = $request->file('photo')->store('faces', 'public');

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'path' => $path,
        ]);
    }
}