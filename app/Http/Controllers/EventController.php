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
    public function index(Request $request): JsonResponse {
        $query = Event::with('club:id,name');

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Filter by club_id
        if ($request->has('club_id')) {
            $query->where('club_id', $request->get('club_id'));
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('start_time', '>=', $request->get('start_date'));
        }
        if ($request->has('end_date')) {
            $query->whereDate('start_time', '<=', $request->get('end_date'));
        }

        // Filter by venue
        if ($request->has('venue')) {
            $query->where('venue', 'like', "%{$request->get('venue')}%");
        }

        // Filter upcoming events only
        if ($request->has('upcoming') && $request->boolean('upcoming')) {
            $query->where('start_time', '>', now());
        }

        // Filter past events only
        if ($request->has('past') && $request->boolean('past')) {
            $query->where('start_time', '<', now());
        }

        // Include trashed items
        if ($request->has('with_trashed') && $request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        // Only trashed items
        if ($request->has('only_trashed') && $request->boolean('only_trashed')) {
            $query->onlyTrashed();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'start_time');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['title', 'start_time', 'end_time', 'venue', 'expected_audience', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Default sorting by start_time
            $query->orderBy('start_time', 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $events = $query->paginate($perPage);

        return response()->json($events);
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

    /**
     * Restore a soft-deleted event.
     */
    public function restore($id): JsonResponse {
        $event = Event::withTrashed()->findOrFail($id);
        
        if (!$event->trashed()) {
            return response()->json([
                'message' => 'Event is not deleted.',
            ], 400);
        }

        $event->restore();
        
        return response()->json([
            'message' => 'Event restored successfully',
            'event' => $event,
        ]);
    }
}