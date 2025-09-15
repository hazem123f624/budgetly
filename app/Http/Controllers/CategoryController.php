<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $categories = Category::query()
            ->whereHas('budget', fn($q) => $q->where('user_id', $userId))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'budget_id' => ['required','exists:budgets,id'],
            'name' => ['required','string','max:255'],
            'type' => ['required','in:income,expense'],
            'limit_amount' => ['nullable','numeric','min:0'],
            'color' => ['nullable','string','max:7'],
        ]);

        $budget = Budget::findOrFail($validated['budget_id']);
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        Category::create($validated);
        return back()->with('success', __('تم إضافة التصنيف'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        if ($category->budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'type' => ['required','in:income,expense'],
            'limit_amount' => ['nullable','numeric','min:0'],
            'color' => ['nullable','string','max:7'],
        ]);

        $category->update($validated);
        return back()->with('success', __('تم تحديث التصنيف'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->budget->user_id !== Auth::id()) {
            abort(403);
        }
        $category->delete();
        return back()->with('success', __('تم حذف التصنيف'));
    }
}
