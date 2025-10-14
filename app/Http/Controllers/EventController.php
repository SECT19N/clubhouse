<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        return response()->json(
            Event::with('club:id,name')
                 ->orderBy('start_time')
                 ->paginate(10)
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'start_time' => 'required|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'venue' => 'nullable|string|max:100',
            'expected_audience' => 'integer|min:5|max:1000',
        ]);

        $event = Event::create($validated);
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): JsonResponse {
        $event->load('club');
        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): JsonResponse {
        $validated = $request->validate([
            'club_id' => 'sometimes|exists:clubs,id',
            'title' => 'sometimes|string|max:200',
            'description' => 'nullable|string|max:2000',
            'start_time' => 'sometimes|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'venue' => 'nullable|string|max:100',
            'expected_audience' => 'integer|min:5|max:1000',
        ]);
        $event->update($validated);
        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse {
        $event->delete();
        return response()->json(null, 204);
    }
}