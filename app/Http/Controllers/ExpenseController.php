<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('addedBy')->latest()->paginate(15);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $users = User::all();
        return view('expenses.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:salary,rent,electricity,equipment,maintenance,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'paid_to'      => 'nullable|string|max:255',
            'added_by'     => 'nullable|exists:users,id',
            'notes'        => 'nullable|string',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
    {
        $users = User::all();
        return view('expenses.edit', compact('expense', 'users'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:salary,rent,electricity,equipment,maintenance,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'paid_to'      => 'nullable|string|max:255',
            'added_by'     => 'nullable|exists:users,id',
            'notes'        => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }

    public function show(Expense $expense)
    {
        return redirect()->route('expenses.index');
    }
}