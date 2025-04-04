<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\PostResource;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event.
     */
    public function index()
    {
        $events = Event::all();
        return new PostResource(true, 'List of Events', $events);
    }

    /**
     * Membuat event baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'required|string|max:255',
            'event_datetime' => 'required|date',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $request['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($request->all());

        return new PostResource(true, 'Event Created Successfully', $event);
    }

    /**
     * Menampilkan detail event beserta daftar kategori tiket terkait.
     */
    public function show($id)
    {
        // Gunakan eager loading untuk mendapatkan ticketCategories
        $event = Event::with('ticketCategories')->find($id);
        if (!$event) {
            return new PostResource(false, 'Event not found', null);
        }

        return new PostResource(true, 'Event Details', $event);
    }

    /**
     * Mengupdate data event.
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return new PostResource(false, 'Event not found', null);
        }

        $request->validate([
            'name'           => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'sometimes|string|max:255',
            'event_datetime' => 'sometimes|date',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $request['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($request->all());

        return new PostResource(true, 'Event Updated Successfully', $event);
    }

    /**
     * Menghapus event.
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return new PostResource(false, 'Event not found', null);
        }

        $event->delete();

        return new PostResource(true, 'Event Deleted Successfully', null);
    }

    /**
     * Mencari event berdasarkan nama atau deskripsi.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $events = Event::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return new PostResource(true, 'Search Results', $events);
    }
}
