<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('vendor.products.show', $product) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition text-sm shadow-sm" title="{{ __('Back to Product') }}">
                    &larr;
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                        {{ __('Product Gallery:') }} {{ $product->name }}
                    </h2>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ __('Category:') }} {{ $product->category->name }} &bull; {{ __('Max 5 images') }}</p>
                </div>
            </div>
            <a href="{{ route('vendor.products.show', $product) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                &larr; {{ __('Back to Product Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200"
         x-data="imageUploadManager('{{ route('vendor.products.images.store', $product) }}', '{{ csrf_token() }}', {{ $product->images->count() }}, 5)">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Dynamic Error Alert -->
            <template x-if="errorMessage">
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm">
                        <span>⚠️</span>
                        <span x-text="errorMessage"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <template x-if="isSessionExpired">
                            <button type="button" @click="window.location.reload()" class="px-3 py-1 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700">{{ __('Refresh Page') }}</button>
                        </template>
                        <button type="button" @click="errorMessage = ''" class="text-xs font-bold text-rose-500 hover:text-rose-700">{{ __('Dismiss') }}</button>
                    </div>
                </div>
            </template>

            <!-- Upload Zone & Progress Card -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>📤 {{ __('Upload Jewellery Images') }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('High-resolution JPG, PNG, WEBP supported (Up to 5MB each).') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-mono"
                              :class="currentCount >= maxCount ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200 dark:border-rose-800' : 'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-700'">
                            <span x-text="currentCount"></span> / <span x-text="maxCount"></span> {{ __('Slots Used') }}
                        </span>
                    </div>
                </div>

                <!-- Dropzone / Input Area -->
                <div x-show="currentCount < maxCount && !isUploading" class="space-y-4">
                    <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-500 rounded-2xl p-6 text-center cursor-pointer transition bg-slate-50/50 dark:bg-slate-950/50 hover:bg-amber-50/20"
                         @dragover.prevent=""
                         @drop.prevent="handleDrop($event)">
                        <input type="file"
                               x-ref="fileInput"
                               @change="handleFiles($event)"
                               multiple
                               accept="image/jpeg,image/png,image/webp,image/jpg"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        
                        <div class="space-y-2 pointer-events-none">
                            <span class="text-4xl">📸</span>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                {{ __('Click to select images or drag and drop here') }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ __('You can select multiple images simultaneously (Remaining:') }} <span class="font-bold text-amber-600 dark:text-amber-400" x-text="remainingSlots"></span>)
                            </p>
                        </div>
                    </div>

                    <!-- Selected Files Pre-Upload Staging Preview -->
                    <template x-if="selectedFiles.length > 0">
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-300">
                                <span>{{ __('Selected for Upload') }} (<span x-text="selectedFiles.length"></span>)</span>
                                <button type="button" @click="clearSelection()" class="text-rose-500 hover:underline">{{ __('Clear') }}</button>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                <template x-for="(file, index) in selectedFiles" :key="index">
                                    <div class="relative rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 overflow-hidden shadow-sm group">
                                        <div class="aspect-square">
                                            <img :src="file.preview" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm text-[10px] truncate text-slate-700 dark:text-slate-300">
                                            <span x-text="file.name" class="font-medium"></span>
                                        </div>
                                        <button type="button"
                                                @click="removeSelectedFile(index)"
                                                class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center shadow hover:bg-rose-700">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button"
                                        @click="uploadFiles()"
                                        class="px-6 py-2.5 bg-gold-gradient text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg hover:brightness-105 transition flex items-center gap-2">
                                    <span>{{ __('Upload') }} <span x-text="selectedFiles.length"></span> {{ __('Image(s) Now') }}</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Live Upload Progress Bar Container -->
                <div x-show="isUploading" class="space-y-4 py-4" x-cloak>
                    <div class="flex items-center justify-between text-xs font-extrabold text-slate-800 dark:text-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                            <span x-text="progressStatus">{{ __('Uploading...') }}</span>
                        </div>
                        <span class="font-mono text-sm text-amber-600 dark:text-amber-400" x-text="`${progressPercent}%`"></span>
                    </div>

                    <!-- Progress Track -->
                    <div class="w-full h-3 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden p-0.5 border border-slate-200 dark:border-slate-700 shadow-inner">
                        <div class="h-full bg-gold-gradient rounded-full transition-all duration-200 relative overflow-hidden"
                             :style="`width: ${progressPercent}%`">
                            <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 text-center">
                        {{ __('Please do not close or refresh this page while your images are transferring.') }}
                    </p>
                </div>

                <!-- Cap Reached Warning -->
                <div x-show="currentCount >= maxCount" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Maximum limit of 5 images reached for this product. Delete an existing image below to upload a new one.') }}
                </div>
            </div>

            <!-- Existing Gallery Grid -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        {{ __('Current Gallery') }} (<span x-text="currentCount">{{ $product->images->count() }}</span>)
                    </h3>
                    <span class="text-xs text-slate-400">{{ __('Sorted by display order') }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @forelse ($product->images as $index => $img)
                        <div class="group relative rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-100 dark:bg-slate-950 shadow-sm transition hover:shadow-md">
                            <div class="aspect-square relative">
                                <img src="{{ $img->url }}" class="w-full h-full object-cover">
                                @if ($index === 0)
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-extrabold tracking-wider uppercase shadow">
                                        {{ __('Primary') }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-900 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-mono text-slate-400">{{ __('Position') }} #{{ $img->sort_order + 1 }}</span>
                                <form method="POST" action="{{ route('vendor.products.images.destroy', [$product, $img]) }}" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Delete Gallery Image') }}', message: '{{ __('Are you sure you want to delete this photo from the product gallery? This will permanently delete the image file.') }}', confirmText: '{{ __('Delete Photo') }}', icon: 'danger', form: this });">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 transition">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400">
                            <span class="text-4xl block mb-2">🖼️</span>
                            <span class="text-sm font-medium">{{ __('No images uploaded for this product yet. Use the upload box above.') }}</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function imageUploadManager(uploadUrl, csrfToken, currentCount, maxCount) {
            return {
                uploadUrl: uploadUrl,
                csrfToken: csrfToken,
                currentCount: currentCount,
                maxCount: maxCount,
                selectedFiles: [],
                isUploading: false,
                progressPercent: 0,
                progressStatus: '',
                errorMessage: '',
                isSessionExpired: false,

                get remainingSlots() {
                    return Math.max(0, this.maxCount - this.currentCount);
                },

                handleFiles(event) {
                    const files = Array.from(event.target.files);
                    this.stageFiles(files);
                },

                handleDrop(event) {
                    const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                    this.stageFiles(files);
                },

                stageFiles(files) {
                    this.errorMessage = '';
                    this.isSessionExpired = false;
                    const totalAllowed = this.remainingSlots - this.selectedFiles.length;

                    if (files.length > totalAllowed) {
                        this.errorMessage = `You can only select up to ${totalAllowed} more image(s). Only the first ${totalAllowed} were selected.`;
                        files = files.slice(0, totalAllowed);
                    }

                    files.forEach(file => {
                        if (file.size > 5 * 1024 * 1024) {
                            this.errorMessage = `File ${file.name} exceeds the 5MB size limit.`;
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.selectedFiles.push({
                                file: file,
                                name: file.name,
                                size: (file.size / 1024 / 1024).toFixed(2),
                                preview: e.target.result
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removeSelectedFile(index) {
                    this.selectedFiles.splice(index, 1);
                },

                clearSelection() {
                    this.selectedFiles = [];
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                },

                uploadFiles() {
                    if (this.selectedFiles.length === 0) return;

                    this.isUploading = true;
                    this.progressPercent = 0;
                    this.progressStatus = `Uploading ${this.selectedFiles.length} image(s)...`;
                    this.errorMessage = '';
                    this.isSessionExpired = false;

                    const formData = new FormData();
                    this.selectedFiles.forEach(item => {
                        formData.append('images[]', item.file);
                    });

                    // Ensure token is attached
                    const token = this.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) {
                        formData.append('_token', token);
                    }

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', this.uploadUrl, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json');

                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }

                    // Real-time Upload Progress listener
                    xhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            this.progressPercent = Math.min(percent, 95);
                            const loadedMB = (e.loaded / 1024 / 1024).toFixed(1);
                            const totalMB = (e.total / 1024 / 1024).toFixed(1);
                            this.progressStatus = `Uploading (${loadedMB} MB / ${totalMB} MB)...`;
                        }
                    });

                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            this.progressPercent = 100;
                            this.progressStatus = 'Processing & saving images...';
                            setTimeout(() => {
                                window.location.reload();
                            }, 600);
                        } else if (xhr.status === 419) {
                            this.isUploading = false;
                            this.isSessionExpired = true;
                            this.errorMessage = 'Session or CSRF token expired. Please refresh the page to continue.';
                        } else {
                            this.isUploading = false;
                            try {
                                const response = JSON.parse(xhr.responseText);
                                this.errorMessage = response.message || 'Upload failed. Please check image format and size.';
                            } catch (e) {
                                this.errorMessage = 'Upload failed. Please try again.';
                            }
                        }
                    };

                    xhr.onerror = () => {
                        this.isUploading = false;
                        this.errorMessage = 'Network connection error during upload.';
                    };

                    xhr.send(formData);
                }
            };
        }
    </script>
</x-app-layout>
