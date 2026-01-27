<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_types = PaymentType::all();
        return view('admin.payment_types.index', compact('payment_types'));
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
        $payload = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (!isset($payload['name'])){
            return redirect()->route('admin.payment_types.index')->with('warning', 'Nama jenis pembayaran harus diisi');
        }

        PaymentType::create([
            'name' => $payload['name']
        ]);

        return redirect()->route('admin.payment_types.index')->with('success', 'Jenis pembayaran baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $payload = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (!isset($payload['name'])){
            return redirect()->route('admin.payment_types.index')->with('warning', 'Nama jenis pembayaran harus diisi');
        }

        $payment_type = PaymentType::findOrFail($id);
        $payment_type->name = $payload['name'];
        $payment_type->save();

        return redirect()->route('admin.payment_types.index')->with('success', 'Jenis pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PaymentType::destroy($id);
        return redirect()->route('admin.payment_types.index')->with('success', 'Jenis pembayaran berhasil dihapus');
    }
}
