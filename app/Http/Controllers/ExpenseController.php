<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display expenses list
     */
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->latest('date')
            ->paginate(15);

        $todayTotal = Expense::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->sum('amount');

        return view('penjaga.expense.index', compact('expenses', 'todayTotal'));
    }

    /**
     * Show create expense form
     */
    public function create()
    {
        $categories = Expense::CATEGORIES;
        return view('penjaga.expense.create', compact('categories'));
    }

    /**
     * Store new expense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'in:belanja_barang,listrik,air,lainnya'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'date.required' => 'Tanggal harus diisi',
            'amount.required' => 'Jumlah harus diisi',
            'amount.numeric' => 'Jumlah harus berupa angka',
            'amount.min' => 'Jumlah tidak boleh negatif',
            'category.required' => 'Kategori harus dipilih',
        ]);

        $expense = Expense::create([
            'user_id' => auth()->id(),
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'notes' => $validated['notes'],
        ]);

        $categoryLabel = Expense::CATEGORIES[$validated['category']]['label'];

        AuditLog::log('input_pengeluaran', "Input pengeluaran ({$categoryLabel}): Rp " . number_format($validated['amount'], 0, ',', '.'), [
            'expense_id' => $expense->id,
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'date' => $validated['date'],
        ]);

        return redirect()->route('penjaga.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil disimpan');
    }
}
