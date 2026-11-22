<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\SupportTicketCategory;
use Illuminate\Http\Request;

class SupportCategoryController extends Controller
{
    public function index()
    {
        $categories = SupportTicketCategory::withCount('tickets')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('support.admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:support_ticket_categories,name',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        SupportTicketCategory::create([
            'name' => trim($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?: 0,
        ]);

        return redirect()->route('admin.support.categories.index')
            ->with('success', 'Ticket category created successfully.');
    }

    public function update($id, Request $request)
    {
        $category = SupportTicketCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:support_ticket_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $category->name = trim($request->name);
        $category->description = $request->description;
        $category->is_active = $request->has('is_active') ? true : false;
        $category->sort_order = $request->sort_order ?: 0;
        $category->save();

        return redirect()->route('admin.support.categories.index')
            ->with('success', 'Ticket category updated successfully.');
    }
}

