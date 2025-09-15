@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold text-center">إضافة معاملة</h1>

    <form method="POST" action="{{ route('transactions.store') }}" class="grid max-w-xl gap-4 mx-auto text-center">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2 rounded bg-white p-6 shadow text-left">
            <label class="block">
                <span class="mb-1 block">الميزانية</span>
                <select name="budget_id" class="w-full rounded border p-2">
                    <option value="" disabled selected>اختر ميزانية</option>
                    @foreach($budgets as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
                @error('budget_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">التصنيف (اختياري)</span>
                <select name="category_id" class="w-full rounded border p-2">
                    <option value="">بدون</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">النوع</span>
                <select name="type" class="w-full rounded border p-2">
                    <option value="expense">مصروف</option>
                    <option value="income">دخل</option>
                </select>
                @error('type')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">التاريخ</span>
                <input name="date" type="date" value="{{ old('date', now()->toDateString()) }}" class="w-full rounded border p-2" />
                @error('date')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block sm:col-span-2">
                <span class="mb-1 block">المبلغ</span>
                <input name="amount" type="number" step="0.01" value="{{ old('amount') }}" class="w-full rounded border p-2" />
                @error('amount')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>


            <label class="block">
                <span class="mb-1 block">الجهة (اختياري)</span>
                <input name="payee" type="text" value="{{ old('payee') }}" class="w-full rounded border p-2" />
                @error('payee')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block sm:col-span-2">
                <span class="mb-1 block">ملاحظات</span>
                <textarea name="notes" rows="3" class="w-full rounded border p-2">{{ old('notes') }}</textarea>
                @error('notes')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>
        </div>

        <div class="mt-4">
            <button class="rounded bg-primary-600 px-4 py-2 text-white hover:bg-primary-700">حفظ</button>
        </div>
    </form>
@endsection
