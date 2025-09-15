<form id="transactionEditForm" action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Header Section -->
    <div class="text-center border-b border-gray-200 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">تعديل المعاملة</h3>
        <p class="text-sm text-gray-600 mt-1">تعديل معاملة في ميزانية: {{ $transaction->budget->name }}</p>
    </div>

    <!-- Form Fields -->
    <div class="space-y-4">
        <!-- Type, Item, Status and Remaining Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Type -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    النوع <span class="text-red-500">*</span>
                </label>
                <select name="type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white modern-select"
                        style="appearance: none !important; -webkit-appearance: none !important; -moz-appearance: none !important; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%236B7280\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6,9 12,15 18,9\'%3e%3c/polyline%3e%3c/svg%3e') !important; background-repeat: no-repeat !important; background-position: right 12px center !important; background-size: 16px !important; padding-right: 40px !important; border-radius: 16px !important; min-height: 52px !important; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;">
                    <option value="">اختر النوع</option>
                    <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }} class="option-income">🟢 دخل</option>
                    <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }} class="option-expense">🔴 مصروف</option>
                </select>
            </div>

            <!-- Item -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    البند <span class="text-red-500">*</span>
                </label>
                <input name="item" type="text" value="{{ $transaction->item }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                       placeholder="أدخل اسم البند" />
            </div>

        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                الوصف
                <span class="text-gray-500 text-xs">(اختياري)</span>
            </label>
            <input name="payee" type="text" value="{{ $transaction->payee }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                   placeholder="وصف المعاملة" />
        </div>

        <!-- Amount and Date Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Amount -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    المبلغ <span class="text-red-500">*</span>
                </label>
                <input name="amount" type="number" step="0.01" value="{{ $transaction->amount }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                       placeholder="0.00" />
            </div>

            <!-- Date -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    التاريخ <span class="text-red-500">*</span>
                </label>
                <input name="date" type="date" value="{{ $transaction->date }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Status -->
            <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                الحالة <span class="text-red-500">*</span>
            </label>
            <select name="status" required id="status-select"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white modern-select"
                    style="appearance: none !important; -webkit-appearance: none !important; -moz-appearance: none !important; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%236B7280\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6,9 12,15 18,9\'%3e%3c/polyline%3e%3c/svg%3e') !important; background-repeat: no-repeat !important; background-position: right 12px center !important; background-size: 16px !important; padding-right: 40px !important; border-radius: 16px !important; min-height: 52px !important; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;">
                <option value="">اختر الحالة</option>
                <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }} class="option-paid">🟢 مدفوعة</option>
                <option value="unpaid" {{ $transaction->status == 'unpaid' ? 'selected' : '' }} class="option-unpaid">🔴 غير مدفوعة</option>
                <option value="partial" {{ $transaction->status == 'partial' ? 'selected' : '' }} class="option-partial">🟡 مدفوعة جزئياً</option>
            </select>
        </div>

           <!-- Remaining Amount (Editable) -->
        <div class="space-y-2" id="remaining-amount-display">
            <label class="block text-sm font-medium text-gray-700">
                الباقي
            </label>
            <div class="relative">
                <input name="remaining_amount" type="number" step="0.01" id="remaining-amount-input"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                       placeholder="0.00" />
                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <span class="text-gray-500 text-sm font-medium">EGP</span>
                </div>
            </div>
        </div>
        </div>

        <!-- Notes -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                ملاحظات
                <span class="text-gray-500 text-xs">(اختياري)</span>
            </label>
            <textarea name="notes" rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400 resize-none"
                      placeholder="أضف أي ملاحظات إضافية...">{{ $transaction->notes }}</textarea>
        </div>
    </div>

    <!-- Error Messages -->
    <div id="formErrors" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="text-sm text-red-600"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
        <button type="submit"
                class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
            <span class="flex items-center">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                تحديث المعاملة
            </span>
        </button>
    </div>
</form>

<script>
// Show/hide fields and calculate remaining amount
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status-select');
    const paidAmountField = document.getElementById('paid-amount-field');
    const remainingDisplay = document.getElementById('remaining-amount-display');
    const amountInput = document.querySelector('input[name="amount"]');
    const paidAmountInput = document.getElementById('paid-amount-input');
    const remainingAmountInput = document.getElementById('remaining-amount-input');

    function calculateRemaining() {
        const amount = parseFloat(amountInput.value) || 0;
        const paidAmount = parseFloat(paidAmountInput.value) || 0;
        const remaining = amount - paidAmount;

        if (remainingAmountInput) {
            remainingAmountInput.value = remaining.toFixed(2);
        }
    }

    if (statusSelect && paidAmountField) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'partial') {
                paidAmountField.style.display = 'block';
                calculateRemaining();
            } else if (this.value === 'paid') {
                paidAmountField.style.display = 'none';
                if (paidAmountInput) paidAmountInput.value = amountInput.value;
                calculateRemaining();
            } else {
                paidAmountField.style.display = 'none';
                if (paidAmountInput) paidAmountInput.value = '0';
                calculateRemaining();
            }
        });
    }

    // Calculate remaining when amount or paid amount changes
    if (amountInput) {
        amountInput.addEventListener('input', calculateRemaining);
    }
    if (paidAmountInput) {
        paidAmountInput.addEventListener('input', calculateRemaining);
    }

    // Initial calculation
    calculateRemaining();

    // Handle form submission
    const form = document.getElementById('transactionEditForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAjaxForm(this);
        });
    }
});

// Define submitAjaxForm function
function submitAjaxForm(formEl) {
    const formData = new FormData(formEl);
    const errorsEl = document.getElementById('formErrors');

    if (errorsEl) {
        errorsEl.classList.add('hidden');
    }

    fetch(formEl.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof showSuccessMessage === 'function') {
                showSuccessMessage(data.message || 'تم التحديث بنجاح');
            } else {
                alert(data.message || 'تم التحديث بنجاح');
            }

            // Close modal
            if (typeof hideAjaxModal === 'function') {
                hideAjaxModal();
            }

            // Reload page or update content
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Show errors
            if (errorsEl && data.errors) {
                const errorText = Object.values(data.errors).flat().join('<br>');
                errorsEl.querySelector('div').innerHTML = errorText;
                errorsEl.classList.remove('hidden');
            }
        }
    })
    .catch(error => {
        if (errorsEl) {
            errorsEl.querySelector('div').textContent = 'حدث خطأ أثناء التحديث';
            errorsEl.classList.remove('hidden');
        }
    });
}
</script>
