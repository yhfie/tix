<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user() ?? User::first();
        $orders = Order::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'items' => 'required|array|min:1',
            'items.*.ticket_id' => 'required|integer|exists:tickets,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        try {
            // transaction
            $order = DB::transaction(function () use ($data, $user) {
                $total = 0;
                // validate stock and calculate total
                foreach ($data['items'] as $it) {
                $t = Ticket::lockForUpdate()->findOrFail($it['ticket_id']);
                if ($t->stock < $it['quantity']) {
                    throw new \Exception("Stok tidak cukup untuk tipe: {$t->tipe}");
                }
                $total += ($t->price ?? 0) * $it['quantity'];
                }

                $order = Order::create([
                'user_id' => $user->id,
                'event_id' => $data['event_id'],
                'order_date' => Carbon::now(),
                'total' => $total,
                ]);

                foreach ($data['items'] as $it) {
                $t = Ticket::findOrFail($it['ticket_id']);
                $subtotal = ($t->price ?? 0) * $it['quantity'];
                OrderDetail::create([
                    'order_id' => $order->id,
                    'ticket_id' => $t->id,
                    'quantity' => $it['quantity'],
                    'subtotal' => $subtotal,
                ]);

                // reduce stock
                $t->stock = max(0, $t->stock - $it['quantity']);
                $t->save();
                }

                return $order;
            });

            // flash success message to session so it appears after redirect
            session()->flash('success', 'Pesanan berhasil dibuat.');

            return response()->json(['ok' => true, 'order_id' => $order->id, 'redirect' => route('orders.index')]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('orderDetails.ticket', 'event');

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
    }
}
