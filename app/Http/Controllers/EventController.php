<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // Menampilkan daftar semua event
    public function index()
    {
        $events = Event::all();

        // Menambahkan URL gambar yang bisa diakses
        $events->each(function ($event) {
            if ($event->image) {
                $event->image_url = Storage::url($event->image);
            }
        });

        return response()->json($events);
    }

    // Membuat event baru
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'location'       => 'required|string|max:255',
            'event_datetime' => 'required|date',
            'image'          => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'location', 'event_datetime']);

        // Upload gambar jika tersedia
        if ($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path     = $image->storeAs('public/events', $filename);
            $data['image'] = $path;
        }

        $event = Event::create($data);

        return response()->json([
            'message' => 'Event berhasil dibuat',
            'event'   => $event
        ], 201);
    }

    // Menampilkan detail event
    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Menambahkan URL gambar
        if ($event->image) {
            $event->image_url = Storage::url($event->image);
        }

        return response()->json($event);
    }

    // Mengupdate data event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name'           => 'sometimes|required|string|max:255',
            'description'    => 'sometimes|required|string',
            'location'       => 'sometimes|required|string|max:255',
            'event_datetime' => 'sometimes|required|date',
            'image'          => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'location', 'event_datetime']);

        // Proses upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($event->image) {
                Storage::delete($event->image);
            }
            $image    = $request->file('image');
            $filename = Str::slug($request->name ?? $event->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path     = $image->storeAs('public/events', $filename);
            $data['image'] = $path;
        }

        $event->update($data);

        return response()->json([
            'message' => 'Event berhasil diupdate',
            'event'   => $event
        ]);
    }

    // Menghapus event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Hapus gambar jika ada
        if ($event->image) {
            Storage::delete($event->image);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil dihapus'
        ]);
    }
}
