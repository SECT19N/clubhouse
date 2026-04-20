<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Event;
use Illuminate\Http\Request;

class WebEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('club:id,name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                    ->orWhere('venue', 'like', "%$s%");
            });
        }

        if ($request->filled('club_id')) {
            $query->where('club_id', $request->club_id);
        }

        if ($request->get('filter') === 'upcoming') {
            $query->where('start_time', '>', now());
        } elseif ($request->get('filter') === 'past') {
            $query->where('start_time', '<', now());
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(12)->withQueryString();
        $clubs = Club::orderBy('name')->get(['id', 'name']);

        return view('events.index', compact('events', 'clubs'));
    }

    public function show(Event $event)
    {
        $event->load('club');

        return view('events.show', compact('event'));
    }

    public function create()
    {
        $clubs = Club::orderBy('name')->get(['id', 'name']);

        return view('events.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'start_time' => 'required|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'venue' => 'nullable|string|max:100',
            'expected_audience' => 'nullable|integer|min:5|max:1000',
        ]);

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $clubs = Club::orderBy('name')->get(['id', 'name']);

        return view('events.edit', compact('event', 'clubs'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'venue' => 'nullable|string|max:100',
            'expected_audience' => 'nullable|integer|min:5|max:1000',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted.');
    }
}
