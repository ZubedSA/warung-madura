<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display incomes list
     */
    public function index()
    {
        $incomes = Income::where('user_id', auth()->id())
            ->latest('date')
            ->paginate(15);

        $todayTotal = Income::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->sum('amount');

        return view('penjaga.income.index', compact('incomes', 'todayTotal'));
    }

    /**
     * Show create income form
     */
    public function create()
    {
        return view('penjaga.income.create');
    }

    /**
     * Store new income
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'date.required' => 'Tanggal harus diisi',
            'amount.required' => 'Jumlah harus diisi',
            'amount.numeric' => 'Jumlah harus berupa angka',
            'amount.min' => 'Jumlah tidak boleh negatif',
        ]);

        $income = Income::create([
            'user_id' => auth()->id(),
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
        ]);

        AuditLog::log('input_pemasukan', "Input pemasukan: Rp " . number_format($validated['amount'], 0, ',', '.'), [
            'income_id' => $income->id,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
        ]);

        return redirect()->route('penjaga.pemasukan.index')
            ->with('success', 'Pemasukan berhasil disimpan');
    }
}
