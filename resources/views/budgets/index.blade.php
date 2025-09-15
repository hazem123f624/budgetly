@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6 max-w-7xl mx-auto">

        <h1 class="text-2xl font-bold">الميزانيات</h1>
        <a href="{{ route('budgets.create') }}" class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 bg-primary-600 text-white shadow hover:bg-primary-700 transition">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-base leading-none">+</span>
            <span class="font-semibold">ميزانية جديدة</span>
        </a>
    </div>

    @if($budgets->isEmpty())
        <div class="rounded border border-dashed p-6 text-center text-gray-500 max-w-7xl mx-auto">لا توجد ميزانيات</div>
    @else
        <!-- Active budgets -->
        <div class="rounded bg-white p-4 shadow max-w-7xl mx-auto">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center border-collapse">
                    <thead class="text-gray-600 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 font-semibold">الاسم</th>
                        <th class="px-6 py-4 font-semibold">البداية</th>
                        <th class="px-6 py-4 font-semibold">النهاية</th>
                        <th class="px-6 py-4 font-semibold">الحالة</th>
                        <th class="px-6 py-4 font-semibold">إجمالي الميزانية</th>
                        <th class="px-6 py-4 font-semibold">إجراءات</th>

                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    @foreach($budgets as $b)
                        @php
                            $isActive = ($b->status ?? null) ? ($b->status === 'active') : (!$b->period_end || (\Carbon\Carbon::parse($b->period_end)->isFuture()));
                            $current = $b->status ?? ($isActive ? 'active' : 'inactive');
                        @endphp
                        @if($isActive)
                            <tr class="bg-white hover:bg-gray-50 transition">
                                <td class="px-6 py-5 font-semibold text-gray-900 text-base">{{ $b->name }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $b->period_start }}</td>
                                <td class="px-6 py-5 text-gray-700">{{ $b->period_end ?? 'مفتوحة' }}</td>
                                <td class="px-6 py-5">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium
                                            {{ $current==='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $current==='active' ? 'نشطة' : 'منتهية' }}
                                        </span>
                                </td>
                                <td class="px-6 py-5 text-gray-800 font-semibold">
                                    {{ number_format($b->total_limit ?? 0, 2) }} {{ $b->currency }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="javascript:void(0)" onclick="openBudgetTransactions({{ $b->id }})" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-1.5 text-white hover:bg-primary-700 transition">
                                            عرض
                                        </a>
                                        <a href="javascript:void(0)" onclick="openBudgetEdit({{ $b->id }})" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-gray-700 hover:bg-gray-200 transition">
                                            تعديل
                                        </a>
                                        <button type="button" onclick="showDeleteModal({{ $b->id }}, '{{ $b->name }}')" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 transition" style="background-color: #dc2626 !important; color: white !important;">
                                            حذف
                                        </button>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                   class="budget-checkbox w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                                                   data-budget-id="{{ $b->id }}"
                                                   data-budget-name="{{ $b->name }}"
                                                   data-budget-income="{{ $b->total_income }}"
                                                   data-budget-expenses="{{ $b->total_expenses }}"
                                                   data-budget-balance="{{ $b->net_balance }}">
                                            <span class="text-sm text-gray-600">عرض في الداشبورد</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $budgets->links() }}</div>
        </div>

        <!-- Inactive budgets -->
        <div class="max-w-7xl mx-auto mt-10">
            <h2 class="mb-4 text-xl font-bold">الميزانيات غير النشطة</h2>
            <div class="rounded bg-white p-4 shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-center border-collapse">
                        <thead class="text-gray-600 bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold">الاسم</th>
                            <th class="px-6 py-4 font-semibold">البداية</th>
                            <th class="px-6 py-4 font-semibold">النهاية</th>
                            <th class="px-6 py-4 font-semibold">الحالة</th>
                            <th class="px-6 py-4 font-semibold">إجمالي الميزانية</th>
                            <th class="px-6 py-4 font-semibold">إجراءات</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y">
                        @php $hasInactive = false; @endphp
                        @foreach($budgets as $b)
                            @php
                                $isActive = ($b->status ?? null) ? ($b->status === 'active') : (!$b->period_end || (\Carbon\Carbon::parse($b->period_end)->isFuture()));
                                $current = $b->status ?? ($isActive ? 'active' : 'inactive');
                            @endphp
                            @if(!$isActive)
                                @php $hasInactive = true; @endphp
                                <tr class="bg-white hover:bg-gray-50 transition">
                                    <td class="px-6 py-5 font-semibold text-gray-900 text-base">{{ $b->name }}</td>
                                    <td class="px-6 py-5 text-gray-700">{{ $b->period_start }}</td>
                                    <td class="px-6 py-5 text-gray-700">{{ $b->period_end ?? 'مفتوحة' }}</td>
                                    <td class="px-6 py-5">
                                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium bg-red-100 text-red-700">
                                                منتهية
                                            </span>
                                    </td>
                                    <td class="px-6 py-5 text-gray-800 font-semibold">
                                        {{ number_format($b->total_limit ?? 0, 2) }} {{ $b->currency }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="javascript:void(0)" onclick="openBudgetTransactions({{ $b->id }})" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-1.5 text-white hover:bg-primary-700 transition">
                                                عرض
                                            </a>
                                            <a href="javascript:void(0)" onclick="openBudgetEdit({{ $b->id }})" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-gray-700 hover:bg-gray-200 transition">
                                                تعديل
                                            </a>
                                            <button type="button" onclick="showDeleteModal({{ $b->id }}, '{{ $b->name }}')" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 transition" style="background-color: #dc2626 !important; color: white !important;">
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if(!$hasInactive)
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-gray-500">لا توجد ميزانيات غير نشطة</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
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
                    هل أنت متأكد من حذف الميزانية
                    <span class="font-semibold text-gray-900" id="budgetName"></span>؟
                    <br>
                    <span class="text-sm text-red-600 mt-2 block">هذا الإجراء لا يمكن التراجع عنه</span>
                </p>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="hideDeleteModal()"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                        إلغاء
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium"
                                style="background-color: #dc2626 !important;">
                            حذف الميزانية
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Define functions directly on this page
async function openBudgetTransactions(budgetId){
    try{
        showAjaxModal('عرض المعاملات', '{{ route("budgets.transactionsSnippet", ":budget") }}'.replace(':budget', budgetId));
        const body = document.getElementById('ajaxModalBody');
        const title = document.getElementById('ajaxModalTitle');
        if(title) title.textContent = 'المعاملات';
        if(body){ body.innerHTML = '<div class="p-6 text-center text-gray-500">جارٍ التحميل...</div>'; }
        const res = await fetch(`/budgets/${budgetId}/transactions-snippet`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        if(!res.ok){ throw new Error('Network response was not ok'); }
        const html = await res.text();
        if(body){ body.innerHTML = html; }
    } catch(err){
        const body = document.getElementById('ajaxModalBody');
        if(body){ body.innerHTML = '<div class="p-6 text-center text-red-600">حدث خطأ أثناء التحميل</div>'; }
    }
}

async function openBudgetEdit(budgetId){
    try{
        showAjaxModal('تعديل الميزانية', '{{ route("budgets.editSnippet", ":budget") }}'.replace(':budget', budgetId));
        const body = document.getElementById('ajaxModalBody');
        const title = document.getElementById('ajaxModalTitle');
        if(title) title.textContent = 'تعديل الميزانية';
        if(body){ body.innerHTML = '<div class="p-6 text-center text-gray-500">جارٍ التحميل...</div>'; }
        const res = await fetch(`/budgets/${budgetId}/edit-snippet`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        if(!res.ok){ throw new Error('Network response was not ok'); }
        const html = await res.text();
        if(body){ body.innerHTML = html; }
    } catch(err){
        const body = document.getElementById('ajaxModalBody');
        if(body){ body.innerHTML = '<div class="p-6 text-center text-red-600">حدث خطأ أثناء التحميل</div>'; }
    }
}

// Define showAjaxModal function
function showAjaxModal(title, url){
    const modal = document.getElementById('ajaxModal');
    const titleEl = document.getElementById('ajaxModalTitle');
    const bodyEl = document.getElementById('ajaxModalBody');

    if(titleEl) titleEl.textContent = title;
    if(bodyEl) bodyEl.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div></div>';

    if(modal) modal.classList.remove('hidden');

    if(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                if(bodyEl) bodyEl.innerHTML = html;
            })
            .catch(error => {
                if(bodyEl) bodyEl.innerHTML = '<div class="text-center py-8 text-red-600">حدث خطأ في تحميل المحتوى</div>';
            });
    }
}

function hideAjaxModal(){
    const m = document.getElementById('ajaxModal');
    if(m) m.classList.add('hidden');
    const body = document.getElementById('ajaxModalBody');
    if(body) body.innerHTML = '';
}

function showDeleteModal(budgetId, budgetName) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const budgetNameElement = document.getElementById('budgetName');
    const deleteForm = document.getElementById('deleteForm');

    // Set budget name
    budgetNameElement.textContent = budgetName;

    // Set form action
    deleteForm.action = `/budgets/${budgetId}`;

    // Show modal with animation
    modal.classList.remove('hidden');

    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');

    // Hide with animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});
function updateCheckboxes() {
    document.querySelectorAll('.budget-checkbox').forEach(checkbox => {
        const budgetId = parseInt(checkbox.dataset.budgetId);
        checkbox.checked = selectedBudgets.some(b => b.id === budgetId);
    });
}
</script>
@endpush
