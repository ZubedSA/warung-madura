<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reports index for admin
     */
    public function index()
    {
        return view('admin.report.index');
    }

    /**
     * Daily report for admin
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $incomes = Income::whereDate('date', $date)
            ->with('user')
            ->latest()
            ->get();

        $expenses = Expense::whereDate('date', $date)
            ->with('user')
            ->latest()
            ->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $profit = $totalIncome - $totalExpense;

        // Expense breakdown by category
        $expenseByCategory = $expenses->groupBy('category')->map(function ($items) {
            return $items->sum('amount');
        });

        return view('admin.report.daily', compact(
            'date',
            'incomes',
            'expenses',
            'totalIncome',
            'totalExpense',
            'profit',
            'expenseByCategory'
        ));
    }

    /**
     * Monthly report for admin
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = \Carbon\Carbon::parse($month)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($month)->endOfMonth();

        // Daily breakdown
        $dailyData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');

            $dailyIncome = Income::whereDate('date', $dateStr)->sum('amount');
            $dailyExpense = Expense::whereDate('date', $dateStr)->sum('amount');

            $dailyData[] = [
                'date' => $currentDate->format('d M'),
                'income' => $dailyIncome,
                'expense' => $dailyExpense,
                'profit' => $dailyIncome - $dailyExpense,
            ];

            $currentDate->addDay();
        }

        // Monthly totals
        $totalIncome = Income::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $totalExpense = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $totalProfit = $totalIncome - $totalExpense;

        // Expense breakdown by category
        $expenseByCategory = Expense::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        return view('admin.report.monthly', compact(
            'month',
            'dailyData',
            'totalIncome',
            'totalExpense',
            'totalProfit',
            'expenseByCategory'
        ));
    }

    /**
     * Finance history for penjaga
     */
    public function penjagaHistory(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $incomes = Income::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->latest('date')
            ->get();

        $expenses = Expense::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->latest('date')
            ->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        return view('penjaga.finance.history', compact(
            'incomes',
            'expenses',
            'totalIncome',
            'totalExpense',
            'startDate',
            'endDate'
        ));
    }
}
