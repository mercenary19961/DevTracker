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
        $perPage = min(request('per_page', 10), 100); // gives users the ability to set per_page, capped at 100
        return NoteResource::collection(
            Note::orderBy('created_at', 'desc')->paginate($perPage)
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
