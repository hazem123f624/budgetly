@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">نظرة عامة على ميزانياتك المختارة</h1>
    </div>

    @if($selected)
    <!-- Selected Budget Summary -->
    <div id="dashboardWidgets" class="mb-8">
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <!-- Total Income Widget -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الدخل</dt>
                            <dd class="text-2xl font-bold text-green-600">{{ number_format($selected->total_income, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Widget -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي المصروفات</dt>
                            <dd class="text-2xl font-bold text-red-600">{{ number_format($selected->total_expenses, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Net Balance Widget -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 {{ $selected->net_balance >= 0 ? 'border-green-500' : 'border-red-500' }}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 {{ $selected->net_balance >= 0 ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">الرصيد الصافي</dt>
                            <dd class="text-2xl font-bold {{ $selected->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($selected->net_balance, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Selected Budget Information -->
    <div id="selectedBudgetsList" class="space-y-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الميزانية المختارة</h3>
            @if($selected)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 text-lg mb-2">{{ $selected->name }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">فترة البداية:</span>
                            <span class="font-medium">{{ $selected->period_start }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">فترة النهاية:</span>
                            <span class="font-medium">{{ $selected->period_end ?? 'مفتوحة' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">الرصيد الحالي:</span>
                            <span class="font-medium {{ $selected->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($selected->net_balance, 2) }} {{ $selected->currency }}
                            </span>
                        </div>
                    </div>
                    @if($selected->notes)
                        <div class="mt-3">
                            <span class="text-gray-600">ملاحظات:</span>
                            <p class="text-gray-800 mt-1">{{ $selected->notes }}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-gray-500 text-center py-8">
                    لا توجد ميزانية مختارة. اذهب إلى صفحة الميزانيات واختر ميزانية واحدة لعرضها هنا.
                </div>
            @endif
        </div>
    </div>

    <script>
        // Close any open status dropdown when clicking outside
        window.addEventListener('click', function (e) {
            document.querySelectorAll('[id^="st-"]').forEach(function(el){
                if(!el.classList.contains('hidden')) el.classList.add('hidden');
            });
        });
    </script>
@endsection
