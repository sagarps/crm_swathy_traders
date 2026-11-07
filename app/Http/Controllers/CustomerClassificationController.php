<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerClassification;
use App\Customer;
use Auth;

class CustomerClassificationController extends Controller
{
    public function index()
    {
        $classifications = CustomerClassification::withCount('customers')->orderBy('name', 'asc')->get();
        return view('customer_classifications.index', compact('classifications'));
    }

    public function create()
    {
        return view('customer_classifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
        ]);

        $name = strtoupper(trim($request->input('name')));

        $existing = CustomerClassification::where('name', $name)->first();
        if ($existing) {
            return redirect()->route('customer_classifications.create')
                ->withInput()
                ->with('exist', 'Customer classification with this name already exists.');
        }

        CustomerClassification::create([
            'name' => $name,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('customer_classifications.index')->with('save', 'Customer classification created successfully.');
    }

    public function edit($id)
    {
        $classification = CustomerClassification::findOrFail($id);
        return view('customer_classifications.edit', compact('classification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:191',
        ]);

        $name = strtoupper(trim($request->input('name')));

        $existing = CustomerClassification::where('name', $name)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->route('customer_classifications.edit', $id)
                ->withInput()
                ->with('exist', 'Customer classification with this name already exists.');
        }

        $classification = CustomerClassification::findOrFail($id);
        $classification->update([
            'name' => $name,
        ]);

        return redirect()->route('customer_classifications.index')->with('update', 'Customer classification updated successfully.');
    }

    public function destroy($id)
    {
        $classification = CustomerClassification::findOrFail($id);

        if ($classification->customers()->count() > 0) {
            return redirect()->route('customer_classifications.index')
                ->with('error', 'Cannot delete classification because customers are currently assigned to it.');
        }

        $classification->delete();

        return redirect()->route('customer_classifications.index')->with('delete', 'Customer classification deleted successfully.');
    }
}

