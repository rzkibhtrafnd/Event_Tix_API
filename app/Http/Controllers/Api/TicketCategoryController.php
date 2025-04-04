<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\Event;
use App\Http\Resources\PostResource;

class TicketCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori tiket.
     */
    public function index()
    {
        $ticketCategories = TicketCategory::with('event')->get();
        return new PostResource(true, 'List of Ticket Categories', $ticketCategories);
    }

    /**
     * Membuat kategori tiket baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id'        => 'required|exists:events,id',
            'category'        => 'required|string|max:255',
            'price'           => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'booking_deadline'=> 'required|date',
        ]);

        // Pastikan event ada
        $event = Event::find($request->event_id);
        if (!$event) {
            return new PostResource(false, 'Event not found', null);
        }

        $ticketCategory = TicketCategory::create($request->all());

        return new PostResource(true, 'Ticket Category Created Successfully', $ticketCategory);
    }

    /**
     * Menampilkan detail kategori tiket.
     */
    public function show($id)
    {
        $ticketCategory = TicketCategory::with('event')->find($id);
        if (!$ticketCategory) {
            return new PostResource(false, 'Ticket Category not found', null);
        }

        return new PostResource(true, 'Ticket Category Details', $ticketCategory);
    }

    /**
     * Mengupdate data kategori tiket.
     */
    public function update(Request $request, $id)
    {
        $ticketCategory = TicketCategory::find($id);
        if (!$ticketCategory) {
            return new PostResource(false, 'Ticket Category not found', null);
        }

        $request->validate([
            'event_id'        => 'sometimes|required|exists:events,id',
            'category'        => 'sometimes|required|string|max:255',
            'price'           => 'sometimes|required|numeric|min:0',
            'stock'           => 'sometimes|required|integer|min:0',
            'booking_deadline'=> 'sometimes|required|date',
        ]);

        if ($request->has('event_id')) {
            $event = Event::find($request->event_id);
            if (!$event) {
                return new PostResource(false, 'Event not found', null);
            }
        }

        $ticketCategory->update($request->all());

        return new PostResource(true, 'Ticket Category Updated Successfully', $ticketCategory);
    }

    /**
     * Menghapus kategori tiket.
     */
    public function destroy($id)
    {
        $ticketCategory = TicketCategory::find($id);
        if (!$ticketCategory) {
            return new PostResource(false, 'Ticket Category not found', null);
        }

        $ticketCategory->delete();

        return new PostResource(true, 'Ticket Category Deleted Successfully', null);
    }
}
