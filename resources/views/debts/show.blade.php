@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $debt->entity_name }}</h1>
                <p class="text-gray-600 mt-2">{{ $debt->source }} </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('debts.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة
                </a>
                <button onclick="openDebtEdit({{ $debt->id }})"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </button>
            </div>
        </div>
    </div>

    <!-- Debt Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Amount -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي الدين</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($debt->amount ?? 0, 2) }} EGP</p>
                </div>
            </div>
        </div>



        <!-- Total Paid -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">المدفوع</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($debt->total_paid ?? 0, 2) }} EGP</p>
                </div>
            </div>
        </div>

        <!-- Remaining -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">المتبقي</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($debt->remaining_amount ?? $debt->amount, 2) }} EGP</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Management -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">معاملات السداد</h2>
                <button onclick="openPaymentAdd({{ $debt->id }})"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة سداد
                </button>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ السداد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة السداد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($debt->payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                {{ number_format($payment->amount, 2) }} EGP
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->payment_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->payment_method ?? 'غير محدد' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $payment->notes ?? 'لا توجد ملاحظات' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openPaymentEdit({{ $payment->id }})"
                                        class="text-blue-600 hover:text-blue-900 mr-3">تعديل</button>
                                <button onclick="deletePayment({{ $payment->id }})"
                                        class="text-red-600 hover:text-red-900">حذف</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2">لا توجد معاملات سداد</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Add Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">إضافة سداد</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="paymentForm" class="flex-1 overflow-y-auto p-6">
                @csrf
                <input type="hidden" name="debt_id" value="{{ $debt->id }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">المبلغ <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" step="0.01" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">تاريخ السداد <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">طريقة السداد</label>
                        <select name="payment_method"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">اختر طريقة السداد</option>
                            <option value="نقدي">نقدي</option>
                            <option value="تحويل بنكي">تحويل بنكي</option>
                            <option value="شيك">شيك</option>
                            <option value="بطاقة ائتمان">بطاقة ائتمان</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">الملاحظات</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="أضف أي ملاحظات..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closePaymentModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        إغلاق
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        إضافة السداد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
function openPaymentAdd(debtId) {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.querySelector('input[name="debt_id"]').value = debtId;
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentForm').reset();
}

function openDebtEdit(debtId) {
    // Implementation for debt edit
    console.log('Edit debt:', debtId);
}

function openPaymentEdit(paymentId) {
    // Implementation for payment edit
    console.log('Edit payment:', paymentId);
}

function deletePayment(paymentId) {
    if (confirm('هل أنت متأكد من حذف هذا السداد؟')) {
        // Implementation for payment delete
        console.log('Delete payment:', paymentId);
    }
}

function showSuccessMessage(message) {
    alert(message);
}

function showErrorMessage(message) {
    alert(message);
}

// Handle payment form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("debt-payments.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            closePaymentModal();
            location.reload();
        } else {
            showErrorMessage(data.message || 'حدث خطأ أثناء إضافة السداد');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('حدث خطأ أثناء إضافة السداد');
    });
});
</script>

