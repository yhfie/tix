<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $eventsQuery = Event::withMin('tickets', 'price')
            ->orderBy('date_time', 'asc');

        if($request->has('category') && $request->category) {
            $eventsQuery->where('category_id', $request->category);
        }

        $events = $eventsQuery->get();

        return view('home', compact('categories', 'events'));
    }
}
