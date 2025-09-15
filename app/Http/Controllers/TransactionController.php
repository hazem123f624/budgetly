<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all budgets with their transactions
        $budgets = Budget::query()
            ->where('user_id', $userId)
            ->with(['transactions' => function($query) {
                $query->with('category:id,name')->latest('date');
            }])
            ->orderBy('name')
            ->get();

        // Calculate totals for each budget
        $budgets->each(function($budget) {
            $budget->total_income = $budget->transactions->where('type', 'income')->sum('amount');
            $budget->total_expenses = $budget->transactions->where('type', 'expense')->sum('amount');
            $budget->net_balance = $budget->total_income - $budget->total_expenses;
        });

        return view('transactions.index', compact('budgets'));
    }

    public function addSnippet(Request $request)
    {
        try {
            $budget = Budget::findOrFail($request->query('budget'));
            $categories = Category::where('budget_id', $budget->id)->get();
            
            return view('transactions.partials.add_form', compact('budget', 'categories'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        $budgets = Budget::query()
            ->where('user_id', Auth::id())
            ->get(['id','name']);

        $categories = Category::query()
            ->whereHas('budget', fn($q) => $q->where('user_id', Auth::id()))
            ->get(['id','name','type','budget_id']);
        return view('transactions.create', compact('budgets','categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'budget_id' => ['required','exists:budgets,id'],
            'type' => ['required','in:income,expense'],
            'item' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'date' => ['required','date'],
            'payee' => ['nullable','string','max:255'],
            'status' => ['required','in:paid,unpaid,partial'],
            'paid_amount' => ['nullable','numeric','min:0'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $budget = Budget::findOrFail($validated['budget_id']);
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }


        $validated['user_id'] = Auth::id();
        $validated['currency'] = 'EGP';
        
        // Log the data being saved
        \Log::info('Creating transaction with data:', $validated);
        
        Transaction::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المعاملة بنجاح'
            ]);
        }

        return redirect()->route('transactions.index')->with('success', __('تم إضافة المعاملة'));
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        $transaction->delete();
        return back()->with('success', __('تم حذف المعاملة'));
    }

    public function editSnippet(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        \Log::info('Edit snippet called for transaction:', ['id' => $transaction->id, 'user_id' => Auth::id()]);

        $budgets = Budget::query()
            ->where('user_id', Auth::id())
            ->get(['id','name']);

        $categories = Category::query()
            ->whereHas('budget', fn($q) => $q->where('user_id', Auth::id()))
            ->get(['id','name','type','budget_id']);

        return view('transactions.partials.edit_form', compact('transaction', 'budgets', 'categories'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'budget_id' => ['required','exists:budgets,id'],
            'type' => ['required','in:income,expense'],
            'item' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'date' => ['required','date'],
            'payee' => ['nullable','string','max:255'],
            'status' => ['required','in:paid,unpaid,partial'],
            'paid_amount' => ['nullable','numeric','min:0'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $budget = Budget::findOrFail($validated['budget_id']);
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المعاملة بنجاح'
            ]);
        }

        return redirect()->route('transactions.index')->with('success', __('تم تحديث المعاملة'));
    }

    public function updateStatus(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required','in:paid,partial,unpaid'],
        ]);

        $transaction->update(['status' => $validated['status']]);

        return back()->with('success', __('تم تحديث الحالة'));
    }
}
