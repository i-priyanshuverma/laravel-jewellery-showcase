<!-- Floating Compact Toast Notifications -->
<div class="fixed top-[84px] right-6 z-50 space-y-3 pointer-events-none">
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="pointer-events-auto max-w-xs p-3.5 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-emerald-300 dark:border-emerald-500/40 text-emerald-800 dark:text-emerald-400 shadow-xl dark:shadow-2xl flex items-center justify-between gap-3 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm font-bold ml-2 focus:outline-none">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="pointer-events-auto max-w-xs p-3.5 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-rose-300 dark:border-rose-500/40 text-rose-800 dark:text-rose-400 shadow-xl dark:shadow-2xl flex items-center justify-between gap-3 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm font-bold ml-2 focus:outline-none">&times;</button>
        </div>
    @endif

    @if (session('warning'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition class="pointer-events-auto max-w-xs p-3.5 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-amber-300 dark:border-amber-500/40 text-amber-800 dark:text-amber-400 shadow-xl dark:shadow-2xl flex items-center justify-between gap-3 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ session('warning') }}</span>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm font-bold ml-2 focus:outline-none">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition class="pointer-events-auto max-w-xs p-3.5 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-sky-300 dark:border-sky-500/40 text-sky-800 dark:text-sky-400 shadow-xl dark:shadow-2xl flex items-center justify-between gap-3 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                <span>{{ session('info') }}</span>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm font-bold ml-2 focus:outline-none">&times;</button>
        </div>
    @endif
</div>
