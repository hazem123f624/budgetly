@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">ميزانية جديدة</h1>
            <p class="text-gray-600 mt-2">قم بإنشاء ميزانية جديدة لإدارة أموالك</p>
        </div>

        <form method="POST" action="{{ route('budgets.store') }}" class="bg-white rounded-lg shadow-lg p-8">
            @csrf

            <!-- Form Fields -->
            <div class="space-y-6">
                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        اسم الميزانية <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input name="name" type="text" value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                               placeholder="أدخل اسم الميزانية" required />
                    </div>
                    @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Date Range Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            تاريخ البداية <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input name="period_start" type="date" value="{{ old('period_start') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                   required />
                        </div>
                        @error('period_start')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            تاريخ النهاية
                            <span class="text-gray-500 text-xs">(اختياري)</span>
                        </label>
                        <div class="relative">
                            <input name="period_end" type="date" value="{{ old('period_end') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" />
                        </div>
                        @error('period_end')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>


                <!-- Notes -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        ملاحظات
                        <span class="text-gray-500 text-xs">(اختياري)</span>
                    </label>
                    <textarea name="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400 resize-none"
                              placeholder="أضف أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-end space-x-3 space-x-reverse">
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        إنشاء الميزانية
                    </span>
                </button>
            </div>
        </form>
    </div>
@endsection
