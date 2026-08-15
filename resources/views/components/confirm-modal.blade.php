<div x-data="{
        isOpen: false,
        title: 'Confirm Action',
        message: 'Are you sure you want to proceed with this action?',
        confirmText: 'Confirm',
        confirmButtonClass: 'bg-rose-600 hover:bg-rose-500 text-white',
        cancelText: 'Cancel',
        icon: 'danger',
        targetForm: null,

        open(data) {
            this.title = data.title || 'Confirm Action';
            this.message = data.message || 'Are you sure you want to proceed?';
            this.confirmText = data.confirmText || 'Confirm';
            this.confirmButtonClass = data.confirmButtonClass || (data.icon === 'warning' ? 'bg-amber-600 hover:bg-amber-500 text-white' : 'bg-rose-600 hover:bg-rose-500 text-white');
            this.cancelText = data.cancelText || 'Cancel';
            this.icon = data.icon || 'danger';
            this.targetForm = data.form || null;
            this.isOpen = true;
        },

        confirm() {
            if (this.targetForm) {
                // If target is a form element or form ID
                if (typeof this.targetForm === 'string') {
                    const formEl = document.getElementById(this.targetForm);
                    if (formEl) formEl.submit();
                } else if (this.targetForm instanceof HTMLFormElement) {
                    this.targetForm.submit();
                }
            }
            this.isOpen = false;
        },

        cancel() {
            this.isOpen = false;
            this.targetForm = null;
        }
     }"
     @open-confirm-modal.window="open($event.detail)"
     @keydown.escape.window="cancel()"
     x-cloak
     class="relative z-[100]">

    <!-- Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Dialog -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4 sm:p-6 text-center">

        <div @click.outside="cancel()"
             class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 sm:p-8 space-y-6">

            <div class="flex items-start gap-4">
                <!-- Dynamic Status Icon -->
                <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-inner"
                     :class="{
                        'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/80': icon === 'danger',
                        'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/80': icon === 'warning',
                        'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80': icon === 'success',
                        'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800/80': icon === 'info'
                     }">
                    <template x-if="icon === 'danger'">
                        <span>⚠️</span>
                    </template>
                    <template x-if="icon === 'warning'">
                        <span>⚡</span>
                    </template>
                    <template x-if="icon === 'success'">
                        <span>✨</span>
                    </template>
                    <template x-if="icon === 'info'">
                        <span>ℹ️</span>
                    </template>
                </div>

                <!-- Text Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white leading-tight" x-text="title"></h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed" x-text="message"></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button"
                        @click="cancel()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/70 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-sm"
                        x-text="cancelText">
                </button>

                <button type="button"
                        @click="confirm()"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                        :class="confirmButtonClass"
                        x-text="confirmText">
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global Confirmation Helper
    window.confirmAction = function(options) {
        window.dispatchEvent(new CustomEvent('open-confirm-modal', {
            detail: options
        }));
    };
</script>
