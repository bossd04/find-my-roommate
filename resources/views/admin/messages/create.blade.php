@extends('admin.layouts.app')

@section('title', 'Compose Message')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Compose Message</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Initiate Outbound Communication</p>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="group flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest rounded-2xl border-2 border-gray-100 dark:border-gray-700 hover:border-indigo-600 hover:text-indigo-600 transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"></i>
                Back to Inbox
            </a>
        </div>

        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data" class="p-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="space-y-2">
                        <label for="receiver_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Recipient</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <select id="receiver_id" name="receiver_id" required
                                class="block w-full pl-12 pr-10 py-4 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-0 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="">Select a user...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                            </div>
                        </div>
                        @error('receiver_id')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="subject" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Subject</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="text" name="subject" id="subject" required
                                   value="{{ old('subject') }}"
                                   class="block w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-bold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 focus:border-indigo-500 transition-all"
                                   placeholder="What is this message about?">
                        </div>
                        @error('subject')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-2 mb-8">
                    <label for="body" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Message Content</label>
                    <textarea id="body" name="body" rows="6" required
                              class="block w-full p-6 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-[2rem] text-sm font-bold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 focus:border-indigo-500 transition-all resize-none"
                              placeholder="Type your official administrative message here...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Section -->
                <div class="space-y-2 mb-10">
                    <label for="image" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Attach Image (Optional)</label>
                    <div class="relative group">
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-[2rem] cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-image text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG or GIF (MAX. 2MB)</p>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(this)" />
                            </label>
                        </div>
                        <!-- Image Preview -->
                        <div id="image-preview" class="hidden mt-4">
                            <div class="relative inline-block">
                                <img id="preview-img" src="" alt="Preview" class="max-h-48 rounded-2xl shadow-lg">
                                <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-2 hover:bg-rose-600 transition-colors shadow-lg">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <i class="fas fa-info-circle mr-2 text-indigo-500"></i>
                        Recipients will receive a system notification immediately.
                    </p>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.messages.index') }}" 
                           class="px-8 py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            Discard
                        </a>
                        <button type="submit" 
                                class="group relative px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all duration-300 shadow-xl shadow-indigo-600/20 overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <i class="fas fa-paper-plane mr-3 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                Send Message
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        input.value = '';
        previewImg.src = '';
        preview.classList.add('hidden');
    }
</script>
@endpush
