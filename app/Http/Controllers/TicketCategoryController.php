<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    // Menampilkan daftar kategori tiket
    public function index()
    {
        $tickets = TicketCategory::with('event')->get();
        return response()->json($tickets);
    }

    // Membuat kategori tiket baru
    public function store(Request $request)
    {
        $request->validate([
            'event_id'         => 'required|exists:events,id',
            'category'         => 'required|string|in:Regular,VIP,VVIP',
            'price'            => 'required|numeric',
            'stock'            => 'required|integer',
            'booking_deadline' => 'required|date',
        ]);

        $ticket = TicketCategory::create($request->all());

        return response()->json([
            'message' => 'Kategori tiket berhasil dibuat',
            'ticket'  => $ticket
        ], 201);
    }

    // Menampilkan detail kategori tiket
    public function show($id)
    {
        $ticket = TicketCategory::with('event')->findOrFail($id);
        return response()->json($ticket);
    }

    // Mengupdate data kategori tiket
    public function update(Request $request, $id)
    {
        $ticket = TicketCategory::findOrFail($id);

        $request->validate([
            'event_id'         => 'sometimes|required|exists:events,id',
            'category'         => 'sometimes|required|string|in:Regular,VIP,VVIP',
            'price'            => 'sometimes|required|numeric',
            'stock'            => 'sometimes|required|integer',
            'booking_deadline' => 'sometimes|required|date',
        ]);

        $ticket->update($request->all());

        return response()->json([
            'message' => 'Kategori tiket berhasil diupdate',
            'ticket'  => $ticket
        ]);
    }

    // Menghapus kategori tiket
    public function destroy($id)
    {
        $ticket = TicketCategory::findOrFail($id);
        $ticket->delete();

        return response()->json([
            'message' => 'Kategori tiket berhasil dihapus'
        ]);
    }
}
