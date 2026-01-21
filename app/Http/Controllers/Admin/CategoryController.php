<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
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
            return redirect()->route('admin.categories.index')->with('status', 'Category must be filled');
        }

        Category::create([
            'name' => $payload['name']
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category created');
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
            return redirect()->route('admin.categories.index')->with('status', 'Category must be filled');
        }

        $category = Category::findOrFail($id);
        $category->name = $payload['name'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('status', 'Category updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::destroy($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted');
    }
}
