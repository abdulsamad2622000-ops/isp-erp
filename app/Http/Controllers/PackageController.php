<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->paginate(15);
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'speed'    => 'required|string|max:100',
            'price'    => 'required|numeric|min:0',
            'validity' => 'required|in:monthly,quarterly,yearly',
            'type'     => 'required|in:fiber,wireless,dsl',
        ]);

        Package::create($request->all());

        return redirect()->route('packages.index')->with('success', 'Package added successfully!');
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'speed'    => 'required|string|max:100',
            'price'    => 'required|numeric|min:0',
            'validity' => 'required|in:monthly,quarterly,yearly',
            'type'     => 'required|in:fiber,wireless,dsl',
        ]);

        $package->update($request->all());

        return redirect()->route('packages.index')->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully!');
    }

    public function show(Package $package)
    {
        return redirect()->route('packages.index');
    }
}