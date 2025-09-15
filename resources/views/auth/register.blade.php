@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md text-center">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold">إنشاء حساب</h1>
            <p class="text-gray-600">ابدأ إدارة ميزانيتك مع Budgetly</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="rounded bg-white p-6 shadow text-left mx-auto max-w-sm">
            @csrf
            <label class="mb-3 block">
                <span class="mb-1 block">الاسم</span>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded border p-2" />
                @error('name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="mb-3 block">
                <span class="mb-1 block">البريد الإلكتروني</span>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded border p-2" />
                @error('email')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="mb-3 block">
                <span class="mb-1 block">كلمة المرور</span>
                <input type="password" name="password" required class="w-full rounded border p-2" />
                @error('password')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </label>

            <label class="mb-4 block">
                <span class="mb-1 block">تأكيد كلمة المرور</span>
                <input type="password" name="password_confirmation" required class="w-full rounded border p-2" />
            </label>

            <button class="w-full rounded bg-primary-600 px-4 py-2 text-white hover:bg-primary-700">إنشاء الحساب</button>
        </form>

        <div class="mt-4 text-center text-sm">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-primary-600 hover:underline">تسجيل الدخول</a>
        </div>
    </div>
@endsection
