<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = Order::latest()->withTrashed()->get();

        return view('admin.history.index', compact('histories'));
    }

    public function show(string $history)
    {
        $order = Order::withTrashed()->findOrFail($history);

        return view('admin.history.show', compact('order'));
    }
}
