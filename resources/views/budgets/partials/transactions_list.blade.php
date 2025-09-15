<div class="p-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">المعاملات الأخيرة - {{ $budget->name }}</h3>
        <a href="{{ route('transactions.index', ['budget' => $budget->id]) }}" class="text-primary-600 hover:text-primary-800">كل المعاملات</a>
    </div>

    @if($transactions->isEmpty())
        <div class="text-gray-500">لا توجد معاملات</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center border-collapse">
                <thead class="text-gray-600 bg-gray-50">
                <tr>
                    <th class="px-4 py-3 font-semibold">التاريخ</th>
                    <th class="px-4 py-3 font-semibold">النوع</th>
                    <th class="px-4 py-3 font-semibold">الفئة</th>
                    <th class="px-4 py-3 font-semibold">المبلغ</th>
                    <th class="px-4 py-3 font-semibold">العملة</th>
                    <th class="px-4 py-3 font-semibold">الجهة</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                @foreach($transactions as $t)
                    <tr class="bg-white hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('transactions.index', ['budget' => $budget->id]) }}'">
                        <td class="px-4 py-3">{{ $t->date }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $t->type==='income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $t->type==='income' ? 'دخل' : 'مصروف' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ optional($t->category)->name ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold">{{ number_format($t->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $t->currency }}</td>
                        <td class="px-4 py-3">{{ $t->payee ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>



