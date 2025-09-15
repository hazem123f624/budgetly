<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $budgets = Budget::query()
            ->where('user_id', $userId)
            ->with(['transactions' => function($query) {
                $query->select('budget_id', 'amount', 'type');
            }])
            ->latest('period_start')
            ->select(['id','name','period_start','period_end','currency','total_limit','status','is_selected','created_at'])
            ->paginate(10)
            ->withQueryString();

        // Calculate actual balance from transactions
        $budgets->getCollection()->each(function($budget) {
            $budget->total_income = $budget->transactions->where('type', 'income')->sum('amount');
            $budget->total_expenses = $budget->transactions->where('type', 'expense')->sum('amount');
            $budget->net_balance = $budget->total_income - $budget->total_expenses;
        });

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        return view('budgets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'period_start' => ['required','date'],
            'period_end' => ['nullable','date','after_or_equal:period_start'],
            'total_limit' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);
        
        // Set default currency
        $validated['currency'] = 'EGP';

        $validated['user_id'] = Auth::id();

        $budget = Budget::create($validated);

        return redirect()->route('budgets.index')->with('success', __('تم إنشاء الميزانية بنجاح'));
    }


    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget = (object) $budget->only(['id','name','period_start','period_end','currency','total_limit','notes','status']);
        return view('budgets.edit', compact('budget'));
    }

    public function transactionsSnippet(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $transactions = $budget->transactions()
            ->latest('date')
            ->with('category:id,name')
            ->limit(20)
            ->get(['id','budget_id','category_id','date','amount','type','payee','currency']);

        return view('budgets.partials.transactions_list', compact('budget','transactions'));
    }

    public function editSnippet(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget = (object) $budget->only(['id','name','period_start','period_end','currency','total_limit','notes','status']);
        return view('budgets.partials.edit_form', compact('budget'));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        // Inline status toggle from listing: only update status and stay on the same page
        $payloadKeys = array_keys($request->except(['_token','_method']));
        if ($request->has('status') && count($payloadKeys) === 1 && in_array('status', $payloadKeys, true)) {
            $request->validate([
                'status' => ['required', 'in:active,inactive'],
            ]);

            $budget->update(['status' => $request->string('status')->toString()]);

            return back()->with('success', __('تم تحديث الحالة'));
        }

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'period_start' => ['required','date'],
            'period_end' => ['nullable','date','after_or_equal:period_start'],
            'currency' => ['sometimes','string','size:3'],
            'total_limit' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
            'status' => ['sometimes','in:active,inactive'],
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')->with('success', __('تم تحديث الميزانية'));
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', __('تم حذف الميزانية'));
    }

    public function select(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // First, unselect all other budgets for this user
            Budget::where('user_id', Auth::id())
                ->where('id', '!=', $budget->id)
                ->update(['is_selected' => false]);

            // Then select the current budget
            $budget->update(['is_selected' => true]);

            return response()->json([
                'success' => true, 
                'message' => 'تم اختيار الميزانية بنجاح',
                'budget_id' => $budget->id,
                'budget_name' => $budget->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'حدث خطأ أثناء اختيار الميزانية: ' . $e->getMessage()
            ], 500);
        }
    }
}
