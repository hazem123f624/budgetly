@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">تعديل الميزانية</h1>

    <form method="POST" action="{{ route('budgets.update', $budget->id) }}" class="grid max-w-3xl gap-4">
        @csrf
        @method('PUT')
        <div class="grid gap-4 sm:grid-cols-2 rounded bg-white p-6 shadow">
            <label class="block">
                <span class="mb-1 block">اسم الميزانية</span>
                <input name="name" type="text" value="{{ old('name', $budget->name) }}" class="w-full rounded border p-2" />
                @error('name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">الحالة</span>
                <select name="status" class="w-full rounded border p-2">
                    <option value="active" {{ old('status', $budget->status ?? 'active') === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ old('status', $budget->status ?? 'inactive') === 'inactive' ? 'selected' : '' }}>منتهية</option>
                </select>
                @error('status')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">تاريخ البداية</span>
                <input name="period_start" type="date" value="{{ old('period_start', $budget->period_start) }}" class="w-full rounded border p-2" />
                @error('period_start')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="block">
                <span class="mb-1 block">تاريخ النهاية (اختياري)</span>
                <input name="period_end" type="date" value="{{ old('period_end', $budget->period_end) }}" class="w-full rounded border p-2" />
                @error('period_end')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>


            <label class="block sm:col-span-2">
                <span class="mb-1 block">ملاحظات</span>
                <textarea name="notes" rows="3" class="w-full rounded border p-2">{{ old('notes', $budget->notes) }}</textarea>
                @error('notes')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>
        </div>

        <div class="mt-4">
            <button class="rounded bg-primary-600 px-4 py-2 text-white hover:bg-primary-700">حفظ التغييرات</button>
        </div>
    </form>
@endsection


