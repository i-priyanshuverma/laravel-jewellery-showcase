<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('Add New Jewellery Product') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl transition-colors">
                <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required placeholder="e.g. Royal Diamond Solitaire Ring" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Detailed product description, design elements, story...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="status" :value="__('Publication Status')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft (Work in Progress)</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Publish upon adding variants)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="flex items-center pt-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-amber-600 focus:ring-amber-500 bg-white dark:bg-slate-900">
                                    <span class="ms-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Feature this product</span>
                                </label>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div x-data="{
                            selectedCount: 0,
                            errorMessage: '',
                            handleFiles(e) {
                                this.errorMessage = '';
                                const files = Array.from(e.target.files);
                                if (files.length > 5) {
                                    this.errorMessage = 'Maximum 5 images allowed for a product. Please re-select up to 5 images.';
                                    e.target.value = '';
                                    this.selectedCount = 0;
                                    return;
                                }
                                for (let f of files) {
                                    if (f.size > 5 * 1024 * 1024) {
                                        this.errorMessage = `File ${f.name} exceeds 5MB limit.`;
                                        e.target.value = '';
                                        this.selectedCount = 0;
                                        return;
                                    }
                                }
                                this.selectedCount = files.length;
                            }
                        }">
                            <div class="flex items-center justify-between">
                                <x-input-label for="images" :value="__('Product Images (Max 5 images)')" />
                                <span class="text-xs font-mono text-slate-400" x-show="selectedCount > 0">
                                    <span class="font-bold text-amber-600 dark:text-amber-400" x-text="selectedCount"></span> / 5 selected
                                </span>
                            </div>

                            <input id="images"
                                   name="images[]"
                                   type="file"
                                   multiple
                                   accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
                                   @change="handleFiles($event)"
                                   class="mt-1 block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 dark:file:bg-amber-500/10 file:text-amber-700 dark:file:text-amber-400 hover:file:bg-amber-100 dark:hover:file:bg-amber-500/20 transition cursor-pointer" />
                            
                            <p class="text-xs text-slate-400 mt-1">Upload up to 5 high-quality photos (JPG, PNG, WEBP, SVG - Max 5MB each).</p>

                            <template x-if="errorMessage">
                                <div class="mt-2 text-xs font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                                    <span>⚠️</span>
                                    <span x-text="errorMessage"></span>
                                </div>
                            </template>

                            <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            <x-input-error class="mt-1" :messages="$errors->get('images.*')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-6">
                        <a href="{{ route('vendor.products.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">Cancel</a>
                        <x-primary-button>Create Product & Continue to Variants &rarr;</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
