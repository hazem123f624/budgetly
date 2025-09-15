<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $budgets = Budget::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get(['id','name']);

        // Get the selected budget from the database
        $selected = Budget::query()
            ->where('user_id', $userId)
            ->where('is_selected', true)
            ->with([
                'categories:id,budget_id,name,type,limit_amount,color',
                'transactions' => function ($q) {
                    $q->latest('date')->limit(15)->with('category:id,name');
                },
            ])
            ->first();

        // Calculate actual balance if budget exists
        if ($selected) {
            $selected->total_income = $selected->transactions->where('type', 'income')->sum('amount');
            $selected->total_expenses = $selected->transactions->where('type', 'expense')->sum('amount');
            $selected->net_balance = $selected->total_income - $selected->total_expenses;
        }

        // Debug: Log the selected budget
        \Log::info('Selected budget:', ['selected' => $selected ? $selected->toArray() : null]);

        $selectedId = $selected ? $selected->id : 0;

        return view('dashboard.index', compact('budgets', 'selected', 'selectedId'));
    }
}




