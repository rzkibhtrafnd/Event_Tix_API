<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TicketCategory;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id'           => 'required|exists:events,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'quantity'           => 'required|integer|min:1',
        ]);

        // Pastikan kategori tiket ada
        $ticketCategory = TicketCategory::find($request->ticket_category_id);
        if (!$ticketCategory) {
            return new PostResource(false, 'Ticket Category not found', null);
        }

        // Cek apakah stok cukup
        if ($ticketCategory->stock < $request->quantity) {
            return new PostResource(false, 'Insufficient stock', null);
        }

        // Hitung total harga
        $totalPrice = $ticketCategory->price * $request->quantity;

        // Buat order baru
        $order = Order::create([
            'user_id'             => Auth::id(),
            'event_id'            => $request->event_id,
            'ticket_category_id'  => $request->ticket_category_id,
            'quantity'            => $request->quantity,
            'total_price'         => $totalPrice,
            'status'              => 'pending',
        ]);

        // Kurangi stok kategori tiket
        $ticketCategory->stock -= $request->quantity;
        $ticketCategory->save();

        return new PostResource(true, 'Order Created Successfully', $order);
    }

    public function history()
    {
        $orders = Order::with(['ticketCategory.event'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return new PostResource(true, 'Order history retrieved successfully', $orders);
    }

}
