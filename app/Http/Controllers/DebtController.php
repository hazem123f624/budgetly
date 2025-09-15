<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Debt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all debts directly from debts table
        $debts = Debt::query()
            ->where('user_id', $userId)
            ->latest('date')
            ->get();

        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function addSnippet(Request $request)
    {
        try {
            return view('debts.partials.add_form');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'date' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'status' => ['nullable','in:paid,unpaid,partial'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['currency'] = 'EGP';
        $validated['status'] = $validated['status'] ?? 'unpaid';
        $validated['remaining_amount'] = $validated['remaining_amount'] ?? $validated['amount'];
        
        Debt::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الدين بنجاح'
            ]);
        }

        return redirect()->route('debts.index')->with('success', __('تم إضافة الدين'));
    }

    public function editSnippet(Debt $debt)
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('debts.partials.edit_form', compact('debt'));
    }

    public function update(Request $request, Debt $debt): RedirectResponse
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'source' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'date' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'status' => ['required','in:paid,unpaid,partial'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $debt->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الدين بنجاح'
            ]);
        }

        return redirect()->route('debts.index')->with('success', __('تم تحديث الدين'));
    }

    public function destroy(Debt $debt): RedirectResponse
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403);
        }

        $debt->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدين بنجاح'
            ]);
        }

        return redirect()->route('debts.index')->with('success', __('تم حذف الدين'));
    }

    public function show(Debt $debt)
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403);
        }

        $debt->load('payments');
        
        return view('debts.show', compact('debt'));
    }
}