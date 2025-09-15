<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Receivable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all receivables directly from receivables table
        $receivables = Receivable::query()
            ->where('user_id', $userId)
            ->latest('date')
            ->get();

        return view('receivables.index', compact('receivables'));
    }

    public function create()
    {
        return view('receivables.create');
    }

    public function addSnippet(Request $request)
    {
        try {
            return view('receivables.partials.add_form');
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
            'status' => ['required','in:paid,unpaid,partial'],
            'paid_amount' => ['nullable','numeric','min:0'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['currency'] = 'EGP';
        
        Receivable::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المستحق بنجاح'
            ]);
        }

        return redirect()->route('receivables.index')->with('success', __('تم إضافة المستحق'));
    }

    public function editSnippet(Receivable $receivable)
    {
        if ($receivable->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('receivables.partials.edit_form', compact('receivable'));
    }

    public function update(Request $request, Receivable $receivable): RedirectResponse
    {
        if ($receivable->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'source' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'date' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'status' => ['required','in:paid,unpaid,partial'],
            'paid_amount' => ['nullable','numeric','min:0'],
            'remaining_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        $receivable->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المستحق بنجاح'
            ]);
        }

        return redirect()->route('receivables.index')->with('success', __('تم تحديث المستحق'));
    }

    public function destroy(Receivable $receivable): RedirectResponse
    {
        if ($receivable->user_id !== Auth::id()) {
            abort(403);
        }

        $receivable->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستحق بنجاح'
            ]);
        }

        return redirect()->route('receivables.index')->with('success', __('تم حذف المستحق'));
    }
}