<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-700 dark:text-slate-200 uppercase tracking-wider shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
