<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('Vendor Profile & Brand Settings') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl transition-colors">
                <form method="POST" action="{{ route('vendor.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Contact Person Name -->
                        <div>
                            <x-input-label for="name" :value="__('Owner / Contact Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Business Name -->
                        <div>
                            <x-input-label for="business_name" :value="__('Business / Brand Name')" />
                            <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $profile->business_name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Contact Phone Number')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $profile->phone)" placeholder="+91 9876543210" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <!-- Address -->
                        <div>
                            <x-input-label for="address" :value="__('Business Address')" />
                            <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm text-slate-900 dark:text-slate-100 transition-colors">{{ old('address', $profile->address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Brand / Store Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm text-slate-900 dark:text-slate-100 transition-colors" placeholder="Tell customers about your craftsmanship, heritage, and specialty...">{{ old('description', $profile->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end border-t border-slate-100 dark:border-slate-800 pt-6">
                        <x-primary-button>Save Profile Changes</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
