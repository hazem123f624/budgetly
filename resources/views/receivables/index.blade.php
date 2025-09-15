@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold">المستحقات لي</h1>
        <button onclick="openReceivableAdd()" class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 bg-green-600 text-white shadow hover:bg-green-700 transition">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-base leading-none">+</span>
            <span class="font-semibold">مستحق جديد</span>
        </button>
    </div>

    @if($receivables->isEmpty())
        <div class="rounded border border-dashed p-6 text-center text-gray-500 max-w-7xl mx-auto">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستحقات</h3>
            <p class="text-gray-500 mb-4">ابدأ بإضافة مستحق جديد</p>
            <button onclick="openReceivableAdd()" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-green-600 text-white hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة مستحق
            </button>
        </div>
    @else
        <div class="rounded bg-white p-4 shadow max-w-7xl mx-auto">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center border-collapse">
                    <thead class="text-gray-600 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 font-semibold">المصدر</th>
                        <th class="px-6 py-4 font-semibold">الوصف</th>
                        <th class="px-6 py-4 font-semibold">المبلغ</th>
                        <th class="px-6 py-4 font-semibold">التاريخ</th>
                        <th class="px-6 py-4 font-semibold">الحالة</th>
                        <th class="px-6 py-4 font-semibold">المتبقي</th>
                        <th class="px-6 py-4 font-semibold">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    @foreach($receivables as $receivable)
                        <tr class="bg-white hover:bg-gray-50 transition">
                            <!-- Source -->
                            <td class="px-6 py-5 font-semibold text-gray-900 text-base">{{ $receivable->source ?? 'بدون مصدر' }}</td>
                            
                            <!-- Description -->
                            <td class="px-6 py-5 text-gray-700">
                                <div class="text-sm font-medium text-gray-900">{{ $receivable->description ?? 'بدون وصف' }}</div>
                            </td>
                            
                            <!-- Amount -->
                            <td class="px-6 py-5 text-gray-800 font-semibold">
                                <div class="text-center">
                                    <span class="text-green-600 font-bold text-lg">{{ number_format($receivable->amount, 2) }}</span>
                                    <span class="text-gray-500 text-sm ml-1">{{ $receivable->currency }}</span>
                                </div>
                            </td>
                            
                            <!-- Date -->
                            <td class="px-6 py-5 text-gray-700">{{ $receivable->date }}</td>
                            
                            <!-- Status -->
                            <td class="px-6 py-5">
                                @php
                                    $status = $receivable->status ?? 'unpaid';
                                    $statusConfig = [
                                        'paid' => ['text' => 'مدفوعة', 'class' => 'bg-green-100 text-green-800'],
                                        'partial' => ['text' => 'مدفوعة جزئياً', 'class' => 'bg-yellow-100 text-yellow-800'],
                                        'unpaid' => ['text' => 'غير مدفوعة', 'class' => 'bg-red-100 text-red-800']
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[$status]['class'] }}">
                                    {{ $statusConfig[$status]['text'] }}
                                </span>
                            </td>
                            
                            <!-- Remaining Amount -->
                            <td class="px-6 py-5 text-gray-800 font-semibold">
                                @if($status === 'partial' && $receivable->remaining_amount)
                                    <span class="text-orange-600 font-bold">{{ number_format($receivable->remaining_amount, 2) }}</span>
                                    <span class="text-gray-500 text-sm ml-1">{{ $receivable->currency }}</span>
                                @elseif($status === 'partial')
                                    <span class="text-orange-600 font-bold">{{ number_format($receivable->amount - ($receivable->paid_amount ?? 0), 2) }}</span>
                                    <span class="text-gray-500 text-sm ml-1">{{ $receivable->currency }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                    <button onclick="openReceivableEdit({{ $receivable->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg transition-colors duration-200">
                                        تعديل
                                    </button>
                                    <button onclick="deleteReceivable({{ $receivable->id }})"
                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-lg transition-colors duration-200">
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
function openReceivableAdd() {
    console.log('Opening receivable add');
    
    let modal = document.getElementById('ajaxModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'ajaxModal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
                <h3 id="ajaxModalTitle" class="text-xl font-semibold">إضافة مستحق جديد</h3>
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
    const title = modal.querySelector('#ajaxModalTitle');
    
    if (title) title.textContent = 'إضافة مستحق جديد';
    if (body) body.innerHTML = '<div class="text-center py-8">جاري التحميل...</div>';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch('/receivables/add-snippet', {
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
        console.error('Error loading receivable form:', error);
        if (body) body.innerHTML = '<div class="text-center py-8 text-red-600">خطأ في تحميل النموذج</div>';
    });
}

function openReceivableEdit(receivableId) {
    console.log('Opening receivable edit for receivable:', receivableId);
    
    let modal = document.getElementById('ajaxModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'ajaxModal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
                <h3 id="ajaxModalTitle" class="text-xl font-semibold">تعديل المستحق</h3>
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
    const title = modal.querySelector('#ajaxModalTitle');
    
    if (title) title.textContent = 'تعديل المستحق';
    if (body) body.innerHTML = '<div class="text-center py-8">جاري التحميل...</div>';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/receivables/${receivableId}/edit-snippet`, {
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
        console.error('Error loading receivable edit form:', error);
        if (body) body.innerHTML = '<div class="text-center py-8 text-red-600">خطأ في تحميل النموذج</div>';
    });
}

function deleteReceivable(receivableId) {
    showDeleteConfirmation(receivableId);
}

function showDeleteConfirmation(receivableId) {
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
                    هل أنت متأكد من حذف هذا المستحق؟
                    <br>
                    <span class="text-sm text-red-600 mt-2 block">هذا الإجراء لا يمكن التراجع عنه</span>
                </p>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="hideDeleteConfirmation()"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                        إغلاق
                    </button>
                    <button type="button" onclick="confirmDelete(${receivableId})"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                        حذف المستحق
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

function confirmDelete(receivableId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/receivables/${receivableId}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    form.submit();
}

function hideAjaxModal() {
    const modal = document.getElementById('ajaxModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endpush