<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DebtPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'debt_id' => ['required', 'exists:debts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        // Check if debt belongs to user
        $debt = Debt::where('id', $validated['debt_id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $validated['user_id'] = Auth::id();
        $validated['currency'] = 'EGP';

        DebtPayment::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة السداد بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم إضافة السداد بنجاح');
    }

    public function update(Request $request, DebtPayment $debtPayment)
    {
        if ($debtPayment->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $debtPayment->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السداد بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث السداد بنجاح');
    }

    public function destroy(DebtPayment $debtPayment)
    {
        if ($debtPayment->user_id !== Auth::id()) {
            abort(403);
        }

        $debtPayment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف السداد بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم حذف السداد بنجاح');
    }
}

