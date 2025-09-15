<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Budgetly') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,600,700&display=swap" rel="stylesheet" />
        <script>
            tailwind = undefined;
            tailwind = tailwind || {};
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: { 600: '#FC7F02', 700: '#E66A00' },
                            accent: { 600: '#7c3aed', 700: '#6d28d9' },
                            muted: '#64748b',
                            sidebar: '#0b2d39',
                        },
                    },
                },
            };
        </script>
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <style>
            body { font-family: 'Cairo', ui-sans-serif, system-ui; }

            /* Force orange color for ALL buttons except specific ones */
            button:not(.modal-close-btn):not([href*="logout"]):not(.logout-btn):not([type="submit"]),
            .btn:not([href*="logout"]):not(.logout-btn):not([type="submit"]),
            input[type="button"]:not([href*="logout"]):not(.logout-btn) {
                background-color: #FC7F02 !important;
                background: #FC7F02 !important;
            }
            button:not(.modal-close-btn):not([href*="logout"]):not(.logout-btn):not([type="submit"]):hover,
            .btn:not([href*="logout"]):not(.logout-btn):not([type="submit"]):hover,
            input[type="button"]:not([href*="logout"]):not(.logout-btn):hover {
                background-color: #E66A00 !important;
                background: #E66A00 !important;
            }

            /* Modal close button - no background */
            .modal-close-btn, button[onclick*="hideAjaxModal"] {
                background-color: transparent !important;
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .modal-close-btn:hover, button[onclick*="hideAjaxModal"]:hover {
                background-color: transparent !important;
                background: transparent !important;
            }

            /* NEW button - gray - highest priority */
            #newMenuButton,
            button#newMenuButton,
            .new-menu-button {
                background-color: #6B7280 !important;
                background: #6B7280 !important;
                background-image: none !important;
            }
            #newMenuButton:hover,
            button#newMenuButton:hover,
            .new-menu-button:hover {
                background-color: #4B5563 !important;
                background: #4B5563 !important;
                background-image: none !important;
            }

            /* Logout button - red - highest priority */
            a[href*="logout"],
            button[onclick*="logout"],
            .logout-btn,
            button[type="submit"],
            form button {
                background-color: #DC2626 !important;
                background: #DC2626 !important;
                color: white !important;
            }

            /* Logout button hover - red darker */
            a[href*="logout"]:hover,
            button[onclick*="logout"]:hover,
            .logout-btn:hover,
            button[type="submit"]:hover,
            form button:hover {
                background-color: #B91C1C !important;
                background: #B91C1C !important;
                color: white !important;
            }

            /* Specific targeting for logout button text */
            button[type="submit"]:contains("تسجيل الخروج"),
            button[type="submit"][value="تسجيل الخروج"] {
                background-color: #DC2626 !important;
                background: #DC2626 !important;
                color: white !important;
            }
            button[type="submit"]:contains("تسجيل الخروج"):hover,
            button[type="submit"][value="تسجيل الخروج"]:hover {
                background-color: #B91C1C !important;
                background: #B91C1C !important;
                color: white !important;
            }

            /* General submit buttons - red */
            button[type="submit"] {
                background-color: #DC2626 !important;
                background: #DC2626 !important;
            }
            button[type="submit"]:hover {
                background-color: #B91C1C !important;
                background: #B91C1C !important;
            }

            /* Specific logout button targeting */
            button[type="submit"][value="تسجيل الخروج"],
            button[type="submit"]:contains("تسجيل الخروج"),
            form[action*="logout"] button,
            form[action*="logout"] button:hover {
                background-color: #DC2626 !important;
                background: #DC2626 !important;
                color: white !important;
            }
            form[action*="logout"] button:hover {
                background-color: #B91C1C !important;
                background: #B91C1C !important;
            }

            /* Override any conflicting styles for logout button */
            button[type="submit"]:not(.bg-primary-600):not(.bg-orange-500):not(.bg-orange-600) {
                background-color: #DC2626 !important;
                background: #DC2626 !important;
                color: white !important;
            }
            button[type="submit"]:not(.bg-primary-600):not(.bg-orange-500):not(.bg-orange-600):hover {
                background-color: #B91C1C !important;
                background: #B91C1C !important;
                color: white !important;
            }

            /* Fallback minimal styles if Tailwind fails to load */
            .bg-sidebar { background-color: #0b2d39; }
            .text-primary-700 { color: #E66A00; }
            .bg-primary-600 { background-color: #FC7F02 !important; }
            .hover\:bg-primary-700:hover { background-color: #E66A00 !important; }
            .text-white { color: #fff; }

            /* Force primary colors with higher specificity */
            button.bg-primary-600,
            a.bg-primary-600,
            .bg-primary-600 {
                background-color: #FC7F02 !important;
                background: #FC7F02 !important;
            }
            button.hover\:bg-primary-700:hover,
            a.hover\:bg-primary-700:hover,
            .hover\:bg-primary-700:hover {
                background-color: #E66A00 !important;
                background: #E66A00 !important;
            }
            .text-primary-600 { color: #FC7F02 !important; }
            .text-primary-700 { color: #E66A00 !important; }
            .border-primary-500 { border-color: #FC7F02 !important; }
            .focus\:ring-primary-500:focus { --tw-ring-color: #FC7F02 !important; }
            .focus\:border-primary-500:focus { border-color: #FC7F02 !important; }

            /* Specific button overrides */
            #newMenuButton { background-color: #FC7F02 !important; }
            #newMenuButton:hover { background-color: #E66A00 !important; }
            button[type="submit"] { background-color: #FC7F02 !important; }
            button[type="submit"]:hover { background-color: #E66A00 !important; }

            /* Modern Professional Select Styling - More Specific */
            select, select.form-control, select[class*="form-"], select[class*="w-full"] {
                appearance: none !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: right 12px center !important;
                background-size: 16px !important;
                padding-right: 40px !important;
                cursor: pointer !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                font-family: inherit !important;
                font-size: 14px !important;
                line-height: 1.5 !important;
                color: #374151 !important;
                background-color: #FFFFFF !important;
                border: 2px solid #E5E7EB !important;
                border-radius: 16px !important;
                padding: 14px 40px 14px 16px !important;
                min-height: 52px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
                width: 100% !important;
            }

            select:focus, select.form-control:focus, select[class*="form-"]:focus, select[class*="w-full"]:focus {
                outline: none !important;
                border-color: #3B82F6 !important;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 4px 16px rgba(0, 0, 0, 0.12) !important;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233B82F6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
                transform: translateY(-1px) !important;
            }

            select:hover:not(:disabled):not(:focus), select.form-control:hover:not(:disabled):not(:focus), select[class*="form-"]:hover:not(:disabled):not(:focus), select[class*="w-full"]:hover:not(:disabled):not(:focus) {
                border-color: #9CA3AF !important;
                background-color: #F9FAFB !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            select:active, select.form-control:active, select[class*="form-"]:active, select[class*="w-full"]:active {
                transform: translateY(0) !important;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
            }

            select:disabled, select.form-control:disabled, select[class*="form-"]:disabled, select[class*="w-full"]:disabled {
                background-color: #F3F4F6 !important;
                color: #9CA3AF !important;
                cursor: not-allowed !important;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23D1D5DB' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
                opacity: 0.6 !important;
            }

            /* Option styling with colored dots */
            select option, select.form-control option, select[class*="form-"] option, select[class*="w-full"] option {
                padding: 12px 16px !important;
                color: #374151 !important;
                background-color: #FFFFFF !important;
                font-size: 14px !important;
                line-height: 1.5 !important;
                position: relative !important;
            }

            select option:hover, select.form-control option:hover, select[class*="form-"] option:hover, select[class*="w-full"] option:hover {
                background-color: #F3F4F6 !important;
            }

            select option:checked, select.form-control option:checked, select[class*="form-"] option:checked, select[class*="w-full"] option:checked {
                background-color: #EFF6FF !important;
                color: #1D4ED8 !important;
                font-weight: 600 !important;
            }

            /* Status indicators for specific options */
            select option[value="active"], select.form-control option[value="active"], select[class*="form-"] option[value="active"], select[class*="w-full"] option[value="active"] {
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310B981'%3e%3ccircle cx='12' cy='12' r='6'%3e%3c/circle%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: left 8px center !important;
                background-size: 8px !important;
                padding-left: 24px !important;
            }

            select option[value="inactive"], select.form-control option[value="inactive"], select[class*="form-"] option[value="inactive"], select[class*="w-full"] option[value="inactive"] {
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23EF4444'%3e%3ccircle cx='12' cy='12' r='6'%3e%3c/circle%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: left 8px center !important;
                background-size: 8px !important;
                padding-left: 24px !important;
            }

            select option[value="paid"], select.form-control option[value="paid"], select[class*="form-"] option[value="paid"], select[class*="w-full"] option[value="paid"] {
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310B981'%3e%3ccircle cx='12' cy='12' r='6'%3e%3c/circle%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: left 8px center !important;
                background-size: 8px !important;
                padding-left: 24px !important;
            }

            select option[value="unpaid"], select.form-control option[value="unpaid"], select[class*="form-"] option[value="unpaid"], select[class*="w-full"] option[value="unpaid"] {
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23EF4444'%3e%3ccircle cx='12' cy='12' r='6'%3e%3c/circle%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: left 8px center !important;
                background-size: 8px !important;
                padding-left: 24px !important;
            }

            select option[value="partial"], select.form-control option[value="partial"], select[class*="form-"] option[value="partial"], select[class*="w-full"] option[value="partial"] {
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23F59E0B'%3e%3ccircle cx='12' cy='12' r='8'%3e%3c/circle%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: left 12px center !important;
                background-size: 12px !important;
                padding-left: 32px !important;
            }

            /* RTL Support for Arabic */
            [dir="rtl"] select, [dir="rtl"] select.form-control, [dir="rtl"] select[class*="form-"], [dir="rtl"] select[class*="w-full"] {
                background-position: left 12px center !important;
                padding-left: 40px !important;
                padding-right: 16px !important;
            }

            [dir="rtl"] select option[value="active"], [dir="rtl"] select.form-control option[value="active"], [dir="rtl"] select[class*="form-"] option[value="active"], [dir="rtl"] select[class*="w-full"] option[value="active"],
            [dir="rtl"] select option[value="inactive"], [dir="rtl"] select.form-control option[value="inactive"], [dir="rtl"] select[class*="form-"] option[value="inactive"], [dir="rtl"] select[class*="w-full"] option[value="inactive"],
            [dir="rtl"] select option[value="paid"], [dir="rtl"] select.form-control option[value="paid"], [dir="rtl"] select[class*="form-"] option[value="paid"], [dir="rtl"] select[class*="w-full"] option[value="paid"],
            [dir="rtl"] select option[value="unpaid"], [dir="rtl"] select.form-control option[value="unpaid"], [dir="rtl"] select[class*="form-"] option[value="unpaid"], [dir="rtl"] select[class*="w-full"] option[value="unpaid"],
            [dir="rtl"] select option[value="partial"], [dir="rtl"] select.form-control option[value="partial"], [dir="rtl"] select[class*="form-"] option[value="partial"], [dir="rtl"] select[class*="w-full"] option[value="partial"] {
                background-position: right 12px center !important;
                padding-right: 32px !important;
                padding-left: 16px !important;
            }

            /* Enhanced select animations */
            .select-focused, select.select-focused, select.form-control.select-focused, select[class*="form-"].select-focused, select[class*="w-full"].select-focused {
                transform: translateY(-1px) !important;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            }

            .select-changed, select.select-changed, select.form-control.select-changed, select[class*="form-"].select-changed, select[class*="w-full"].select-changed {
                animation: selectPulse 0.4s ease-in-out !important;
            }

            .select-hover, select.select-hover, select.form-control.select-hover, select[class*="form-"].select-hover, select[class*="w-full"].select-hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            }

            @keyframes selectPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }

        </style>
    </head>
    <body class="min-h-screen bg-gray-50">
        <nav class="bg-gray-100 border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-xl font-extrabold text-primary-700">Budgetly</a>
                    @if(!request()->routeIs('login') && !request()->routeIs('register'))
                    <div class="relative -translate-x-10">
                        <button id="newMenuButton" onclick="console.log('Button clicked'); toggleNewMenu(event);" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-medium transition text-white shadow-sm" style="background-color: #6B7280 !important; background: #6B7280 !important;" onmouseover="this.style.setProperty('background-color', '#4B5563', 'important'); this.style.setProperty('background', '#4B5563', 'important');" onmouseout="this.style.setProperty('background-color', '#6B7280', 'important'); this.style.setProperty('background', '#6B7280', 'important');">
                            <span class="text-lg">+</span>
                            <span>NEW</span>
                        </button>
                        <div id="newMenu" class="absolute right-0 mt-2 w-56 rounded-lg border border-gray-200 bg-white shadow-lg hidden z-50">
                            <a href="{{ route('budgets.create') }}" class="block px-4 py-2 hover:bg-gray-50">ميزانية جديدة</a>
                            <a href="{{ route('transactions.create') }}" class="block px-4 py-2 hover:bg-gray-50">معاملة جديدة</a>
                            <a href="{{ route('debts.create') }}" class="block px-4 py-2 hover:bg-gray-50">دين جديد</a>
                            <a href="{{ route('receivables.create') }}" class="block px-4 py-2 hover:bg-gray-50">مستحقات جديدة</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-medium transition text-white shadow-sm" style="background-color: #DC2626 !important;" onmouseover="this.style.backgroundColor='#B91C1C'" onmouseout="this.style.backgroundColor='#DC2626'">تسجيل الخروج</button>
                    </form>
                    @endauth
                </div>
            </div>
        </nav>
        <div id="ajaxModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/40" onclick="hideAjaxModal()"></div>
            <div class="absolute inset-x-0 top-10 mx-auto w-11/12 max-w-4xl rounded-lg bg-white shadow-lg">
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <h3 id="ajaxModalTitle" class="text-lg font-semibold"></h3>
                    <button class="text-gray-500 hover:text-gray-700 modal-close-btn" onclick="hideAjaxModal()">✕</button>
                </div>
                <div id="ajaxModalBody" class="max-h-[70vh] overflow-y-auto"></div>
            </div>
        </div>
        <script>
            function toggleNewMenu(e){
                console.log('toggleNewMenu called');
                if(e) e.stopPropagation();
                const m = document.getElementById('newMenu');
                if(m) {
                    m.classList.toggle('hidden');
                } else {
                    console.error('newMenu element not found');
                }
            }
            window.addEventListener('click', function(){
                const m = document.getElementById('newMenu');
                if(m && !m.classList.contains('hidden')) m.classList.add('hidden');
            });

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
                            console.error('Error:', error);
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

            // Budget functions
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
            function showSuccessMessage(message){
                const notification = document.getElementById('successNotification');
                const messageEl = document.getElementById('successMessage');
                if(notification && messageEl) {
                    messageEl.textContent = message;
                    notification.classList.remove('hidden');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000);
                }
            }


            // Enhanced Select Interactions
            document.addEventListener('DOMContentLoaded', function() {
                // Add enhanced interactions to all select elements
                const selects = document.querySelectorAll('select');
                selects.forEach(select => {
                    // Add focus/blur animations
                    select.addEventListener('focus', function() {
                        this.classList.add('select-focused');
                    });
                    
                    select.addEventListener('blur', function() {
                        this.classList.remove('select-focused');
                    });
                    
                    // Add change animation
                    select.addEventListener('change', function() {
                        this.classList.add('select-changed');
                        setTimeout(() => {
                            this.classList.remove('select-changed');
                        }, 400);
                    });
                    
                    // Add hover effects
                    select.addEventListener('mouseenter', function() {
                        this.classList.add('select-hover');
                    });
                    
                    select.addEventListener('mouseleave', function() {
                        this.classList.remove('select-hover');
                    });
                });
            });

            // Force colors on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Force NEW button color - gray with multiple attempts
                const newButton = document.getElementById('newMenuButton');
                if (newButton) {
                    // Multiple ways to force the color
                    newButton.style.setProperty('background-color', '#6B7280', 'important');
                    newButton.style.setProperty('background', '#6B7280', 'important');
                    newButton.setAttribute('style', 'background-color: #6B7280 !important; background: #6B7280 !important;');

                    newButton.addEventListener('mouseenter', function() {
                        this.style.setProperty('background-color', '#4B5563', 'important');
                        this.style.setProperty('background', '#4B5563', 'important');
                    });
                    newButton.addEventListener('mouseleave', function() {
                        this.style.setProperty('background-color', '#6B7280', 'important');
                        this.style.setProperty('background', '#6B7280', 'important');
                    });
                }

                // Also try after a short delay
                setTimeout(() => {
                    const newBtn = document.getElementById('newMenuButton');
                    if (newBtn) {
                        newBtn.style.setProperty('background-color', '#6B7280', 'important');
                        newBtn.style.setProperty('background', '#6B7280', 'important');
                    }

                    // Force logout button again after delay
                    const logoutBtns = document.querySelectorAll('button[type="submit"], a[href*="logout"], button[onclick*="logout"], .logout-btn, form button');
                    logoutBtns.forEach(btn => {
                        if (btn.textContent.includes('تسجيل الخروج') ||
                            btn.href?.includes('logout') ||
                            btn.classList.contains('logout-btn') ||
                            btn.closest('form')?.action?.includes('logout')) {

                            btn.style.setProperty('background-color', '#DC2626', 'important');
                            btn.style.setProperty('background', '#DC2626', 'important');
                            btn.style.setProperty('color', 'white', 'important');
                            btn.setAttribute('style', 'background-color: #DC2626 !important; background: #DC2626 !important; color: white !important;');

                            // Remove conflicting classes
                            btn.classList.remove('bg-primary-600', 'bg-orange-500', 'bg-orange-600');
                        }
                    });
                }, 100);

                // Force logout button every 500ms to ensure it stays red
                setInterval(() => {
                    const logoutBtns = document.querySelectorAll('button[type="submit"], form button');
                    logoutBtns.forEach(btn => {
                        if (btn.textContent.includes('تسجيل الخروج') ||
                            btn.closest('form')?.action?.includes('logout')) {

                            if (btn.style.backgroundColor !== 'rgb(220, 38, 38)' &&
                                !btn.style.backgroundColor.includes('#DC2626')) {
                                btn.style.setProperty('background-color', '#DC2626', 'important');
                                btn.style.setProperty('background', '#DC2626', 'important');
                                btn.style.setProperty('color', 'white', 'important');
                                btn.classList.remove('bg-primary-600', 'bg-orange-500', 'bg-orange-600');
                            }
                        }
                    });
                }, 500);

                // Force all primary buttons
                document.querySelectorAll('.bg-primary-600, button[class*="bg-primary"]').forEach(btn => {
                    btn.style.backgroundColor = '#FC7F02';
                    btn.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#E66A00';
                    });
                    btn.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '#FC7F02';
                    });
                });

                // Force logout button - red (multiple selectors)
                const logoutButtons = document.querySelectorAll('button[type="submit"], a[href*="logout"], button[onclick*="logout"], .logout-btn, form button');
                logoutButtons.forEach(logoutBtn => {
                    if (logoutBtn.textContent.includes('تسجيل الخروج') ||
                        logoutBtn.href?.includes('logout') ||
                        logoutBtn.classList.contains('logout-btn') ||
                        logoutBtn.closest('form')?.action?.includes('logout')) {

                        // Force red color with multiple methods
                        logoutBtn.style.setProperty('background-color', '#DC2626', 'important');
                        logoutBtn.style.setProperty('background', '#DC2626', 'important');
                        logoutBtn.style.setProperty('color', 'white', 'important');
                        logoutBtn.setAttribute('style', 'background-color: #DC2626 !important; background: #DC2626 !important; color: white !important;');

                        // Remove any existing classes that might override
                        logoutBtn.classList.remove('bg-primary-600', 'bg-orange-500', 'bg-orange-600');

                        // Remove any existing hover listeners
                        logoutBtn.removeEventListener('mouseenter', function() {});
                        logoutBtn.removeEventListener('mouseleave', function() {});

                        logoutBtn.addEventListener('mouseenter', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            this.style.setProperty('background-color', '#B91C1C', 'important');
                            this.style.setProperty('background', '#B91C1C', 'important');
                            this.style.setProperty('color', 'white', 'important');
                        });
                        logoutBtn.addEventListener('mouseleave', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            this.style.setProperty('background-color', '#DC2626', 'important');
                            this.style.setProperty('background', '#DC2626', 'important');
                            this.style.setProperty('color', 'white', 'important');
                        });
                }
            });

            async function openTransactionAdd(budgetId){

                // Validate budgetId
                if (!budgetId || budgetId === 'undefined' || budgetId === 'null') {
                    alert('خطأ: معرف الميزانية غير صحيح');
                    return;
                }

                try {
                    // Check if modal exists in DOM
                    let existingModal = document.getElementById('ajaxModal');

                    // Check if showAjaxModal exists
                    if (typeof showAjaxModal === 'function') {
                        showAjaxModal('إضافة معاملة جديدة', '{{ route("transactions.add-snippet") }}?budget_id=' + budgetId);
                    } else {
                        createModalManually();
                    }

                    // Load content
                    const body = document.getElementById('ajaxModalBody');
                    const title = document.getElementById('ajaxModalTitle');

                    if (title) {
                        title.textContent = 'إضافة معاملة جديدة';
                    }
                    if (body) {
                        body.innerHTML = '<div class="p-6 text-center text-gray-500">جارٍ التحميل...</div>';
                    }

                    const res = await fetch(`/transactions/add-snippet?budget_id=${budgetId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                    }

                    const html = await res.text();

                    if (body) {
                        body.innerHTML = html;
                    }

                    // Show the modal
                    const modal = document.getElementById('ajaxModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                    }

                } catch (err) {
                    const body = document.getElementById('ajaxModalBody');
                    if (body) {
                        body.innerHTML = `
                            <div class="p-6 text-center text-red-600">
                                <h3 class="text-lg font-semibold mb-2">حدث خطأ أثناء التحميل</h3>
                                <p class="text-sm">${err.message}</p>
                                <button onclick="hideAjaxModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    إغلاق
                                </button>
                            </div>
                        `;
                    } else {
                        alert('خطأ: ' + err.message);
                    }
                }
            }

            function createModalManually() {
                // Create modal if it doesn't exist
                let modal = document.getElementById('ajaxModal');
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'ajaxModal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden">
                            <div class="flex items-center justify-between p-6 border-b">
                                <h3 id="ajaxModalTitle" class="text-lg font-semibold text-gray-900">إضافة معاملة جديدة</h3>
                                <button onclick="hideAjaxModal()" class="text-gray-400 hover:text-gray-600 modal-close-btn">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="ajaxModalBody" class="overflow-y-auto max-h-[70vh]">
                                <div class="p-6 text-center text-gray-500">جارٍ التحميل...</div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                }
                modal.classList.remove('hidden');
            }

            // Fallback for hideAjaxModal if it doesn't exist
            if (typeof hideAjaxModal !== 'function') {
                window.hideAjaxModal = function() {
                    const modal = document.getElementById('ajaxModal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                };
            }


            // Add event listener for clicking outside modal
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('ajaxModal');
                if (modal && !modal.classList.contains('hidden') && e.target === modal) {
                    hideAjaxModal();
                }
            });


            // Add event listener for Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('ajaxModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        hideAjaxModal();
                    }
                }
            });

            async function submitAjaxForm(e, formEl){
                e.preventDefault();
                const formData = new FormData(formEl);
                const action = formEl.getAttribute('action');
                const spoofed = (formEl.querySelector('input[name=_method]')?.value || '').toUpperCase();
                const formMethod = (formEl.getAttribute('method') || 'POST').toUpperCase();
                const isSpoofedMethod = spoofed === 'PUT' || spoofed === 'PATCH' || spoofed === 'DELETE';
                const method = isSpoofedMethod ? 'POST' : formMethod; // Always POST when spoofing to ensure PHP parses form-data
                const errorsEl = document.getElementById('ajaxFormErrors');
                if(errorsEl) errorsEl.textContent = '';
                try{
                    const res = await fetch(action, {
                        method,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formEl.querySelector('input[name=_token]').value },
                        body: formData
                    });
                    if(res.ok){
                        hideAjaxModal();
                        showSuccessMessage('تم التعديل بنجاح');
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                        return false;
                    }
                    const data = await res.json().catch(()=>null);
                    if(data && data.errors){
                        const messages = Object.values(data.errors).flat().join('، ');
                        if(errorsEl) {
                            errorsEl.textContent = messages;
                            errorsEl.classList.remove('hidden');
                        }
                    } else {
                        if(errorsEl) {
                            errorsEl.textContent = 'حدث خطأ أثناء الحفظ';
                            errorsEl.classList.remove('hidden');
                        }
                    }
                } catch(err){
                    if(errorsEl) errorsEl.textContent = 'تعذر الاتصال بالخادم';
                    if(errorsEl) errorsEl.classList.remove('hidden');
                }
                return false;
            }
        </script>
        <main class="w-full p-0" onclick="document.querySelectorAll('[id^=b-st-]').forEach(el=>el.classList.add('hidden'))">
            <div class="grid grid-cols-12">
                @if(!request()->routeIs('login') && !request()->routeIs('register'))
                <aside class="col-span-3 lg:col-span-2 md:block min-h-screen bg-sidebar text-white">
                    <nav class="p-4 space-y-1">
                        <a href="{{ route('dashboard') }}" class="block rounded px-3 py-2 hover:bg-white/10">الصفحة الرئيسية</a>
                        <a href="{{ route('budgets.index') }}" class="block rounded px-3 py-2 hover:bg-white/10">الميزانيات</a>
                        <a href="{{ route('transactions.index') }}" class="block rounded px-3 py-2 hover:bg-white/10">المعاملات</a>
                        <a href="{{ route('debts.index') }}" class="block rounded px-3 py-2 hover:bg-white/10">الديون</a>
                        <a href="{{ route('receivables.index') }}" class="block rounded px-3 py-2 hover:bg-white/10">المستحقات</a>
                    </nav>
                </aside>
                <section class="col-span-12 md:col-span-9 lg:col-span-10 p-6 bg-gray-50">
                @else
                <section class="col-span-12 p-6 bg-gray-50">
                @endif
            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
            @endif
            <div id="successNotification" class="fixed top-4 right-4 z-50 hidden">
                <div class="rounded bg-green-100 border border-green-400 text-green-700 px-4 py-3 shadow-lg">
                    <span id="successMessage"></span>
                </div>
            </div>
            @yield('content')
                </section>
            </div>
        </main>

        @stack('scripts')
    </body>
</html>
