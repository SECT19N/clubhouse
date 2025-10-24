<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        return response()->json(Club::withCount([
            'students',
            'events'
        ])->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // used for HTML Forms
    }

    /**
     * Store a newly created resource in storage. returns 201 on success
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:clubs',
            'room' => 'nullable|string|max:30',
            'founded_year' => 'required|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);

        $club = Club::create($validated);
        return response()->json($club, 201);
    }

    /**
     * Display the specified resource. route: Api/club/{id}
     */
    public function show(Club $club): JsonResponse {
        $club->load([
            'students',
            'events'
        ]);

        return response()->json($club);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Club $club)
    {
        // used for HTML Forms, provides a pre-filled form to edit.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Club $club): JsonResponse {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:120|unique:clubs,name,' . $club->id,
            'room' => 'nullable|string|max:30',
            'founded_year' => 'sometimes|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);
        
        $club->update($validated);
        return response()->json($club);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Club $club): JsonResponse {
        $club->delete();
        return response()->json(null, 204);
    }
}
