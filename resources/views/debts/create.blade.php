@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">إضافة دين جديد</h1>
        <p class="text-gray-600 mt-2">إضافة دين جديد إلى قائمة الديون</p>
    </div>

    <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">إضافة دين جديد</h3>
        <p class="mt-1 text-sm text-gray-500">اضغط على الزر أدناه لإضافة دين جديد</p>
        <div class="mt-6">
            <button onclick="openDebtAdd()"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                إضافة دين جديد
            </button>
        </div>
    </div>
@endsection

<script>
function openDebtAdd() {
    console.log('Opening debt add');
    
    let modal = document.getElementById('ajaxModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'ajaxModal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex justify-between items-center border-b px-6 py-4 flex-shrink-0">
                <h3 id="ajaxModalTitle" class="text-xl font-semibold">إضافة دين جديد</h3>
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
    
    if (title) title.textContent = 'إضافة دين جديد';
    if (body) body.innerHTML = '<div class="text-center py-8">جاري التحميل...</div>';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/debts/add-snippet?budget=${budgetId}`, {
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

function hideAjaxModal() {
    const modal = document.getElementById('ajaxModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>