@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">المعاملات</h1>
        <p class="text-gray-600 mt-2">إدارة جميع معاملاتك المالية</p>
    </div>

    @if($budgets->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد ميزانيات</h3>
            <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء ميزانية أولاً لإضافة المعاملات</p>
            <div class="mt-6">
                <a href="{{ route('budgets.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    إنشاء ميزانية جديدة
                </a>
            </div>
        </div>
    @else
        <div class="space-y-8">
            @foreach($budgets as $budget)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Budget Header -->
                    <div class="bg-gradient-to-r from-primary-50 to-primary-100 px-6 py-4 border-b border-primary-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $budget->name }}</h2>
                                    <p class="text-sm text-gray-600 mt-1">{{ $budget->period_start }}
                                        - {{ $budget->period_end ?? 'مفتوحة' }}</p>
                                </div>
                            </div>
                            <div>
                                <button onclick="openTransactionAdd({{ $budget->id }})"
                                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    إضافة معاملة
                                </button>
                            </div>
                        </div>

                        <!-- Budget Summary -->
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($budget->total_income, 2) }}</div>
                                <div class="text-sm text-gray-600">إجمالي الدخل</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ number_format($budget->total_expenses, 2) }}</div>
                                <div class="text-sm text-gray-600">إجمالي المصروفات</div>
                            </div>
                            <div class="text-center">
                                <div
                                    class="text-2xl font-bold {{ $budget->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($budget->net_balance, 2) }}
                                </div>
                                <div class="text-sm text-gray-600">الرصيد الصافي</div>
                            </div>
                        </div>
    </div>

                    <!-- Transactions Table -->
                    <div class="overflow-x-auto">
                        @if($budget->transactions->isEmpty())
                            <div class="p-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2">لا توجد معاملات في هذه الميزانية</p>
                            </div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البند</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القيمة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الباقي</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($budget->transactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <!-- Type -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $transaction->type === 'income' ? 'دخل' : 'مصروف' }}
                                                </span>
                                        </td>

                                        <!-- Item -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->item ?? 'بدون بند' }}</div>
                                        </td>

                                        <!-- Description -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->payee ?? 'بدون وصف' }}</div>
                                            <div class="text-sm text-gray-500">{{ optional($transaction->category)->name ?? 'بدون فئة' }}</div>
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="text-sm font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                            </div>
                                        </td>

                                        <!-- Date -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->date }}
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $transaction->status ?? 'unpaid';
                                                $statusConfig = [
                                                    'paid' => ['text' => 'مدفوعة', 'class' => 'bg-green-100 text-green-800'],
                                                    'partial' => ['text' => 'مدفوعة جزئياً', 'class' => 'bg-yellow-100 text-yellow-800'],
                                                    'unpaid' => ['text' => 'غير مدفوعة', 'class' => 'bg-red-100 text-red-800']
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[$status]['class'] }}">
                                                {{ $statusConfig[$status]['text'] }}
                                            </span>
                                        </td>

                                        <!-- Remaining Amount -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($status === 'partial' && $transaction->remaining_amount)
                                                {{ number_format($transaction->remaining_amount, 2) }} {{ $transaction->currency }}
                                            @elseif($status === 'partial')
                                                {{ number_format($transaction->amount - ($transaction->paid_amount ?? 0), 2) }} {{ $transaction->currency }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                                <button onclick="openTransactionEdit({{ $transaction->id }})"
                                                        class="px-3 py-1 text-xs border border-gray-300 rounded hover:bg-gray-100 transition-colors duration-150 text-gray-600 hover:bg-gray-300"
                                                        style="background-color: #E5E7EB !important; background: #E5E7EB !important;"
                                                        onmouseover="this.style.setProperty('background-color', '#D1D5DB', 'important'); this.style.setProperty('background', '#D1D5DB', 'important');"
                                                        onmouseout="this.style.setProperty('background-color', '#E5E7EB', 'important'); this.style.setProperty('background', '#E5E7EB', 'important');">
                                                    تعديل
                                                </button>
                                                <button onclick="deleteTransaction({{ $transaction->id }})"
                                                        class="px-3 py-1 text-xs border border-red-400 rounded hover:bg-red-100 transition-colors duration-150 text-white hover:bg-red-600"
                                                        style="background-color: #DC2626 !important; background: #DC2626 !important;"
                                                        onmouseover="this.style.setProperty('background-color', '#B91C1C', 'important'); this.style.setProperty('background', '#B91C1C', 'important');"
                                                        onmouseout="this.style.setProperty('background-color', '#DC2626', 'important'); this.style.setProperty('background', '#DC2626', 'important');">
                                                    حذف
                                                </button>
                    </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const transactionAddSnippetUrl = @json(route('transactions.add-snippet'));

        function openTransactionAdd(budgetId) {
            const url = `${transactionAddSnippetUrl}?budget=${encodeURIComponent(budgetId)}`;

            // لو عندك دالة showAjaxModal الأصلية
            if (typeof showAjaxModal === 'function') {
                return showAjaxModal('إضافة معاملة جديدة', url);
            }

            // fallback modal
            let modal = document.getElementById('ajaxModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'ajaxModal';
                modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
                        <h3 id="ajaxModalTitle" class="text-xl font-semibold">إضافة معاملة جديدة</h3>
                        <button id="ajaxModalClose" type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">إغلاق</button>
                    </div>
                    <div id="ajaxModalBody" class="p-6 overflow-y-auto flex-1">
                        <div class="text-center py-8">جاري التحميل...</div>
                    </div>
                </div>`;
                document.body.appendChild(modal);
                document.getElementById('ajaxModalClose').addEventListener('click', hideAjaxModal);
                modal.addEventListener('click', e => { if (e.target === modal) hideAjaxModal(); });
            }

            const body = modal.querySelector('#ajaxModalBody');
            modal.classList.remove('hidden');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => body.innerHTML = html)
                .catch(err => body.innerHTML = `<div class="text-red-500 p-4">خطأ: ${err.message}</div>`);
        }

        function hideAjaxModal() {
            const modal = document.getElementById('ajaxModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openTransactionEdit(transactionId) {
            console.log('Opening edit for transaction:', transactionId);

            // Get or create modal
            let modal = document.getElementById('ajaxModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'ajaxModal';
                modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
                        <h3 id="ajaxModalTitle" class="text-xl font-semibold">تعديل المعاملة</h3>
                        <button id="ajaxModalClose" type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">إغلاق</button>
                    </div>
                    <div id="ajaxModalBody" class="p-6 overflow-y-auto flex-1">
                        <div class="text-center py-8">جاري التحميل...</div>
                    </div>
                </div>`;
                document.body.appendChild(modal);
                document.getElementById('ajaxModalClose').addEventListener('click', hideAjaxModal);
                modal.addEventListener('click', e => { if (e.target === modal) hideAjaxModal(); });
            }

            const title = document.getElementById('ajaxModalTitle');
            const body = document.getElementById('ajaxModalBody');

            console.log('Modal elements:', { modal, title, body });

            if (title) title.textContent = 'تعديل المعاملة';
            if (body) body.innerHTML = '<div class="p-6 text-center text-gray-500">جارٍ التحميل...</div>';

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            console.log('Modal shown');

            // Fetch edit form
            const url = `/transactions/${transactionId}/edit-snippet`;
            console.log('Fetching from URL:', url);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                console.log('Response status:', res.status);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.text();
            })
            .then(html => {
                console.log('Received HTML length:', html.length);
                if (body) body.innerHTML = html;
            })
            .catch(err => {
                console.error('Error:', err);
                if (body) body.innerHTML = '<div class="p-6 text-center text-red-600">حدث خطأ أثناء التحميل: ' + err.message + '</div>';
            });
        }

        function deleteTransaction(transactionId) {
            showDeleteConfirmation(transactionId);
        }

        function showDeleteConfirmation(transactionId) {
            // Create confirmation modal
            const modal = document.createElement('div');
            modal.id = 'deleteConfirmationModal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all duration-300">
                    <div class="p-6">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">تأكيد الحذف</h3>

                        <!-- Message -->
                        <p class="text-gray-600 text-center mb-6">
                            هل أنت متأكد من حذف هذه المعاملة؟
                            <br>
                            <span class="text-sm text-red-600 mt-2 block">هذا الإجراء لا يمكن التراجع عنه</span>
                        </p>

                        <!-- Buttons -->
                        <div class="flex gap-3 justify-center">
                            <button type="button" onclick="hideDeleteConfirmation()"
                                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                                إغلاق
                            </button>
                            <button type="button" onclick="confirmDelete(${transactionId})"
                                    class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                                حذف المعاملة
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideDeleteConfirmation();
                }
            });
        }

        function hideDeleteConfirmation() {
            const modal = document.getElementById('deleteConfirmationModal');
            if (modal) {
                modal.remove();
            }
        }

        function confirmDelete(transactionId) {
            // Hide modal first
            hideDeleteConfirmation();

            // Show loading
            if (typeof showSuccessMessage === 'function') {
                showSuccessMessage('جارٍ حذف المعاملة...');
            }

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/transactions/${transactionId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }

        // Budget selection for dashboard
        let selectedBudgets = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Load saved selections from localStorage
            const saved = localStorage.getItem('selectedBudgets');
            if (saved) {
                selectedBudgets = JSON.parse(saved);
                updateCheckboxes();
            }

            // Add event listeners to checkboxes
            document.querySelectorAll('.budget-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const budgetId = parseInt(this.dataset.budgetId);
                    const budgetData = {
                        id: budgetId,
                        name: this.dataset.budgetName,
                        income: parseFloat(this.dataset.budgetIncome),
                        expenses: parseFloat(this.dataset.budgetExpenses),
                        balance: parseFloat(this.dataset.budgetBalance)
                    };

                    if (this.checked) {
                        // Add to selected budgets
                        if (!selectedBudgets.find(b => b.id === budgetId)) {
                            selectedBudgets.push(budgetData);
                        }
                    } else {
                        // Remove from selected budgets
                        selectedBudgets = selectedBudgets.filter(b => b.id !== budgetId);
                    }

                    // Save to localStorage
                    localStorage.setItem('selectedBudgets', JSON.stringify(selectedBudgets));

                    // Update dashboard if it exists
                    updateDashboard();
                });
            });
        });

        function updateCheckboxes() {
            document.querySelectorAll('.budget-checkbox').forEach(checkbox => {
                const budgetId = parseInt(checkbox.dataset.budgetId);
                checkbox.checked = selectedBudgets.some(b => b.id === budgetId);
            });
        }

        function updateDashboard() {
            // This will be called when dashboard is loaded
            if (typeof window.updateDashboardWidgets === 'function') {
                window.updateDashboardWidgets(selectedBudgets);
            }
        }

        // Export function for dashboard to use
        window.getSelectedBudgets = function() {
            return selectedBudgets;
        };
    </script>
@endpush
