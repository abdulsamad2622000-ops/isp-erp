<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::latest()->paginate(15);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'city'      => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'sub_area'  => 'nullable|string|max:255',
            'coverage_details' => 'nullable|string',
        ]);

        Area::create($request->all());

        return redirect()->route('areas.index')->with('success', 'Area added successfully!');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'city'      => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'sub_area'  => 'nullable|string|max:255',
            'coverage_details' => 'nullable|string',
        ]);

        $area->update($request->all());

        return redirect()->route('areas.index')->with('success', 'Area updated successfully!');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully!');
    }

    public function show(Area $area)
    {
        return redirect()->route('areas.index');
    }
}