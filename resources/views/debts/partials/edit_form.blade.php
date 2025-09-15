<form id="debtEditForm" method="POST" action="{{ route('debts.update', $debt->id) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Header Section -->
    <div class="text-center border-b border-gray-200 pb-4">
        <h3 class="text-lg font-semibold text-gray-900">تعديل الدين</h3>
        <p class="text-sm text-gray-600 mt-1">تعديل بيانات الدين</p>
    </div>

    <!-- Form Fields -->
    <div class="space-y-8">
        <!-- Form Fields in Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Source -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    المصدر <span class="text-red-500">*</span>
                </label>
                <input name="source" type="text" required value="{{ $debt->source }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                       placeholder="أدخل مصدر الدين" />
            </div>

            <!-- Amount -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    المبلغ <span class="text-red-500">*</span>
                </label>
                <input name="amount" type="number" step="100" required value="{{ $debt->amount }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                       placeholder="0.00" />

            </div>

            <!-- Date -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    التاريخ <span class="text-red-500">*</span>
                </label>
                <input name="date" type="date" required value="{{ $debt->date }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" />
            </div>
            <!-- Remaining Amount Row -->
            <div class="space-y-2" id="remaining-amount-display">
                <label class="block text-sm font-medium text-gray-700">
                    المتبقي
                </label>
                <div class="relative">
                    <input name="remaining_amount" type="number" step="100" id="remaining-amount-input" value="{{ $debt->remaining_amount }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                           placeholder="0.00" />

                </div>
            </div>
        </div>



    </div>

    <!-- Error Messages -->
    <div id="formErrors" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="text-sm text-red-600"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
        <button type="submit"
                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
            <span class="flex items-center">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                تحديث الدين
            </span>
        </button>
    </div>
</form>

<script>
// Calculate remaining amount
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const remainingAmountInput = document.getElementById('remaining-amount-input');

    function calculateRemaining() {
        const amount = parseFloat(amountInput.value) || 0;
        const remaining = amount; // For now, remaining = amount

        if (remainingAmountInput) {
            remainingAmountInput.value = remaining.toFixed(2);
        }
    }

    // Calculate remaining when amount changes
    if (amountInput) {
        amountInput.addEventListener('input', calculateRemaining);
    }

    // Initial calculation
    calculateRemaining();

    // Handle form submission
    const form = document.getElementById('debtEditForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Calculate remaining amount before submission
            calculateRemaining();

            console.log('Form data:', new FormData(this));
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
