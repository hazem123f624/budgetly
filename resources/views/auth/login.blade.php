@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md text-center">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold">تسجيل الدخول</h1>
            <p class="text-gray-600">ادخل بيانات حسابك للوصول إلى Budgetly</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="rounded bg-white p-6 shadow text-left mx-auto max-w-sm">
            @csrf
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

            <div class="mb-4 flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300" />
                    <span>تذكرني</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-primary-600 hover:underline">نسيت كلمة المرور؟</a>
            </div>

            <button class="w-full rounded bg-primary-600 px-4 py-2 text-white hover:bg-primary-700">تسجيل الدخول</button>
        </form>

        <div class="mt-4 text-center text-sm">
            ليس لديك حساب؟ <a href="{{ route('register') }}" class="text-primary-600 hover:underline">إنشاء حساب</a>
        </div>
    </div>
@endsection
