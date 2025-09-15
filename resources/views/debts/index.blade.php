@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">الديون</h1>
        <p class="text-gray-600 mt-2">إدارة جميع ديونك المالية</p>
    </div>


    @if($debts->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد ديون</h3>
            <p class="mt-1 text-sm text-gray-500">ابدأ بإضافة دين جديد</p>
            <div class="mt-6">
                <button onclick="openDebtAdd()"
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة دين
                </button>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">قائمة الديون</h2>
                            <p class="text-sm text-gray-600">إجمالي {{ $debts->count() }} دين</p>
                        </div>
                    </div>
                    <button onclick="openDebtAdd()"
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        إضافة دين جديد
                    </button>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                المصدر
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                المبلغ
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                تاريخ السداد
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                الإجراءات
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($debts as $debt)
                        <tr class="hover:bg-red-50 transition-all duration-200 group">
                            <!-- Source -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                                        <span class="text-red-600 font-bold text-sm">{{ substr($debt->source ?? 'د', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $debt->source ?? 'بدون مصدر' }}</div>
                                        @if($debt->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($debt->description, 30) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    <div class="text-lg font-bold text-red-600">
                                        {{ number_format($debt->amount, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500 font-medium">
                                        {{ $debt->currency ?? 'EGP' }}
                                    </div>
                                </div>
                                @if($debt->remaining_amount && $debt->remaining_amount != $debt->amount)
                                    <div class="text-xs text-gray-500 mt-1">
                                        متبقي: {{ number_format($debt->remaining_amount, 2) }} {{ $debt->currency ?? 'EGP' }}
                                    </div>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($debt->date)->format('d/m/Y') }}
                                    </div>
                                </div>

                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('debts.show', $debt->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        عرض
                                    </a>
                                    <button onclick="openDebtEdit({{ $debt->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-all duration-200 shadow-sm hover:shadow-md"
                                            style="background-color: #E5E7EB !important; color: #374151 !important;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        تعديل
                                    </button>
                                    <button onclick="deleteDebt({{ $debt->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md"
                                            style="background-color: #DC2626 !important; color: white !important;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            @if($debts->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            إجمالي الديون: <span class="font-semibold text-gray-900">{{ $debts->count() }}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            إجمالي المبلغ: <span class="font-bold text-red-600">{{ number_format($debts->sum('amount'), 2) }} {{ $debts->first()->currency ?? 'EGP' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
<script>
// Set up CSRF token for all AJAX requests
window.Laravel = {
    csrfToken: '{{ csrf_token() }}'
};
function openDebtAdd() {
    console.log('Opening debt add');

    // Remove existing modal if any
    let existingModal = document.getElementById('ajaxModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Create new modal
    let modal = document.createElement('div');
    modal.id = 'ajaxModal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
    modal.innerHTML = `
    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col" style="width: 60vw !important; height: 60vh !important; margin: 0.25rem !important;">
        <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
            <h3 id="ajaxModalTitle" class="text-xl font-semibold">إضافة دين جديد</h3>
            <button id="ajaxModalClose" type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-sm">إغلاق</button>
        </div>
        <div id="ajaxModalBody" class="p-12 overflow-y-auto flex-1">
            <div class="text-center py-8">جاري التحميل...</div>
        </div>
    </div>`;
    document.body.appendChild(modal);
    const closeButton = document.getElementById('ajaxModalClose');
    if (closeButton) {
        closeButton.addEventListener('click', hideAjaxModal);
    }
    modal.addEventListener('click', e => { if (e.target === modal) hideAjaxModal(); });

    const body = modal.querySelector('#ajaxModalBody');
    const title = modal.querySelector('#ajaxModalTitle');

    if (title) title.textContent = 'إضافة دين جديد';
    if (body) body.innerHTML = '<div class="text-center py-8">جاري التحميل...</div>';

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    fetch('/debts/add-snippet', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.text())
    .then(html => {
        if (body) body.innerHTML = html;
    })
    .catch(error => {
        console.error('Error loading debt form:', error);
        if (body) body.innerHTML = '<div class="text-center py-8 text-red-600">خطأ في تحميل النموذج</div>';
    });
}

function openDebtEdit(debtId) {
    console.log('Opening debt edit for debt:', debtId);

    // Remove existing modal if any
    let existingModal = document.getElementById('ajaxModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Create new modal
    let modal = document.createElement('div');
    modal.id = 'ajaxModal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
    modal.innerHTML = `
    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col" style="width: 60vw !important; height: 60vh !important; margin: 0.25rem !important;">
        <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
            <h3 id="ajaxModalTitle" class="text-xl font-semibold">تعديل الدين</h3>
            <button id="ajaxModalClose" type="button" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-sm">إغلاق</button>
        </div>
        <div id="ajaxModalBody" class="p-12 overflow-y-auto flex-1">
            <div class="text-center py-8">جاري التحميل...</div>
        </div>
    </div>`;
    document.body.appendChild(modal);
    const closeButton = document.getElementById('ajaxModalClose');
    if (closeButton) {
        closeButton.addEventListener('click', hideAjaxModal);
    }
    modal.addEventListener('click', e => { if (e.target === modal) hideAjaxModal(); });

    const body = modal.querySelector('#ajaxModalBody');
    const title = modal.querySelector('#ajaxModalTitle');

    if (title) title.textContent = 'تعديل الدين';
    if (body) body.innerHTML = '<div class="text-center py-8">جاري التحميل...</div>';

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    fetch(`/debts/${debtId}/edit-snippet`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.text())
    .then(html => {
        if (body) body.innerHTML = html;
    })
    .catch(error => {
        console.error('Error loading debt edit form:', error);
        if (body) body.innerHTML = '<div class="text-center py-8 text-red-600">خطأ في تحميل النموذج</div>';
    });
}

function deleteDebt(debtId) {
    showDeleteConfirmation(debtId);
}

function showDeleteConfirmation(debtId) {
    const modal = document.createElement('div');
    modal.id = 'deleteConfirmationModal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">تأكيد الحذف</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-600">
                    هل أنت متأكد من حذف هذا الدين؟
                    <br>
                    <span class="text-sm text-red-600 mt-2 block">هذا الإجراء لا يمكن التراجع عنه</span>
                </p>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="hideDeleteConfirmation()"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                        إغلاق
                    </button>
                    <button type="button" onclick="confirmDelete(${debtId})"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                        حذف الدين
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    modal.addEventListener('click', e => { if (e.target === modal) hideDeleteConfirmation(); });
}

function hideDeleteConfirmation() {
    const modal = document.getElementById('deleteConfirmationModal');
    if (modal) {
        modal.remove();
    }
}

function confirmDelete(debtId) {
    // Hide confirmation modal first
    if (typeof hideDeleteConfirmation === 'function') {
        hideDeleteConfirmation();
    }
    
    // Show loading message
    if (typeof showSuccessMessage === 'function') {
        showSuccessMessage('جارٍ حذف الدين...');
    }

    // Get CSRF token
    const csrfToken = window.Laravel?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('خطأ: لم يتم العثور على CSRF token');
        return;
    }

    // Use fetch to delete
    fetch(`/debts/${debtId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.ok) {
            if (typeof showSuccessMessage === 'function') {
                showSuccessMessage('تم حذف الدين بنجاح');
            } else {
                alert('تم حذف الدين بنجاح');
            }
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error('فشل في حذف الدين');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حذف الدين: ' + error.message);
    });
}

function hideAjaxModal() {
    const modal = document.getElementById('ajaxModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function hideDeleteConfirmation() {
    // This function can be implemented if needed
    console.log('Hide delete confirmation called');
}

function showSuccessMessage(message) {
    // Simple alert for now, can be enhanced later
    alert(message);
}
</script>
@endpush
