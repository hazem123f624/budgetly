<form method="POST" action="{{ route('budgets.update', $budget->id) }}" class="p-6" onsubmit="return submitAjaxForm(event, this)">
    @csrf
    @method('PUT')
    
    <!-- Header Section -->
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h3 class="text-xl font-semibold text-gray-900">تعديل الميزانية</h3>
        <p class="text-sm text-gray-600 mt-1">قم بتحديث تفاصيل الميزانية</p>
    </div>

    <!-- Form Fields -->
    <div class="space-y-6">
        <!-- Name and Status Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    اسم الميزانية <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input name="name" type="text" value="{{ $budget->name }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400" 
                           placeholder="أدخل اسم الميزانية" required />
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    الحالة <span class="text-red-500">*</span>
                </label>
                <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white modern-select"
                        style="appearance: none !important; -webkit-appearance: none !important; -moz-appearance: none !important; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%236B7280\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6,9 12,15 18,9\'%3e%3c/polyline%3e%3c/svg%3e') !important; background-repeat: no-repeat !important; background-position: right 12px center !important; background-size: 16px !important; padding-right: 40px !important; border-radius: 16px !important; min-height: 52px !important; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;">
                    <option value="active" {{ ($budget->status ?? 'active') === 'active' ? 'selected' : '' }} class="option-active">🟢 نشطة</option>
                    <option value="inactive" {{ ($budget->status ?? 'active') === 'inactive' ? 'selected' : '' }} class="option-inactive">🔴 منتهية</option>
                </select>
            </div>
        </div>

        <!-- Date Range Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    تاريخ البداية <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input name="period_start" type="date" value="{{ $budget->period_start }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" 
                           required />
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    تاريخ النهاية
                    <span class="text-gray-500 text-xs">(اختياري)</span>
                </label>
                <div class="relative">
                    <input name="period_end" type="date" value="{{ $budget->period_end }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" />
                </div>
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
                      placeholder="أضف أي ملاحظات إضافية...">{{ $budget->notes }}</textarea>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex items-center justify-end">
        <button type="submit" 
                class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
            <span class="flex items-center">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                حفظ التغييرات
            </span>
        </button>
    </div>

    <!-- Error Messages -->
    <div id="ajaxFormErrors" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 hidden"></div>
</form>

