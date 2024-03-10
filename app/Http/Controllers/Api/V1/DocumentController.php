<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentStoreRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): DocumentCollection
    {
        $tasks = Document::orderBy("created_at", "desc")->paginate(10);

        return new DocumentCollection($tasks);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentStoreRequest $request): DocumentResource|JsonResponse
    {
        try {
            $validated = $request->validated();
            $file_name = time() . "_" . $validated['file']->getClientOriginalName();

            $validated['file'] = $file_name;

            $document = Auth::user()->documents()->create($validated);

            $request->file('file')->storeAs('public/uploads', $file_name);

            return new  DocumentResource($document);

        } catch (\Exception $e) {
            Log::error("Internal server error => " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, try again.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): DocumentResource
    {
        return new DocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentUpdateRequest $request, Document $document)
    {
        try {
            $validated = $request->validated();

            if (!empty($validated['file'])) {
                $file_name = time() . "_" . $validated['file']->getClientOriginalName();

                $validated['file'] = $file_name;

                $request->file('file')->storeAs('public/uploads', $file_name);
            }

            $document->update($validated);


            return new DocumentResource($document);

        } catch (\Exception $e) {
            Log::error("Internal server error => " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, try again.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): Response
    {
        $document->delete();

        return response()->noContent();
    }
}
