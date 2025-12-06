<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;

class NoteController extends Controller
{
    public function index()
    {
        $query = Note::query();

        // Search filter
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = request('sort', 'created_at');
        $direction = request('direction', 'desc');

        // Whitelist allowed sortable columns for safety
        $allowedSorts = ['id', 'title', 'created_at', 'updated_at'];
        $allowedDirections = ['asc', 'desc'];

        if (in_array($sort, $allowedSorts) && in_array($direction, $allowedDirections)) {
            $query->orderBy($sort, $direction);
        } else {
            // Fallback to default if invalid parameters
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = min(request('per_page', 10), 100);

        return NoteResource::collection(
            $query->paginate($perPage)
        );
    }

    public function show(Note $note)
    {
        return new NoteResource($note);
    }

    public function store(StoreNoteRequest $request)
    {
        $note = Note::create($request->validated());

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->validated());

        return new NoteResource($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json(['message' => 'Note deleted successfully'], 204);
    }
}
