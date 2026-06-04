<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::latest()->paginate(15);
        return view('inventory.index', compact('inventory'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|in:router,onu,cable,switch,splitter,other',
            'total_stock' => 'required|integer|min:0',
            'unit_price'  => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['available_stock'] = $request->total_stock;

        Inventory::create($data);

        return redirect()->route('inventory.index')->with('success', 'Item added successfully!');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|in:router,onu,cable,switch,splitter,other',
            'total_stock' => 'required|integer|min:0',
            'unit_price'  => 'nullable|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Item deleted successfully!');
    }

    public function show(Inventory $inventory)
    {
        return redirect()->route('inventory.index');
    }
}