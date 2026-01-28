<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $event->load(['tickets', 'category', 'user']);
        $payment_types = PaymentType::all();

        return view('events.show', compact('event'), compact('payment_types'));
    }
}
