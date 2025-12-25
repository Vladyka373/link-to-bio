<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('➕ Добавить новую ссылку') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto justify-center">
                👤 В профиль
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- 📝 Показываем ошибки валидации --}}
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <strong>❌ Ошибки валидации:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{-- 📝 Форма для создания ссылки --}}
                    <form action="{{ route('links.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">📝 Название ссылки:</label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title') }}" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('title')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">🔗 URL ссылки:</label>
                            <div class="flex items-center gap-3">
                                <div id="social-icon" class="text-2xl min-w-[30px] text-center hidden">
                                    <!-- Иконка будет вставлена через JavaScript -->
                                </div>
                                <input 
                                    type="text" 
                                    name="url" 
                                    id="url" 
                                    value="{{ old('url') }}" 
                                    placeholder="example.com или https://example.com" 
                                    required
                                    class="flex-1 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    oninput="detectSocialNetwork(this.value)"
                                >
                            </div>
                            @error('url')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <script>
                            function detectSocialNetwork(url) {
                                const iconContainer = document.getElementById('social-icon');
                                if (!url || url.trim() === '') {
                                    iconContainer.classList.add('hidden');
                                    return;
                                }
                                
                                // Нормализуем URL (добавляем протокол если нет)
                                let normalizedUrl = url.toLowerCase();
                                if (!normalizedUrl.startsWith('http://') && !normalizedUrl.startsWith('https://')) {
                                    normalizedUrl = 'https://' + normalizedUrl;
                                }
                                
                                try {
                                    const urlObj = new URL(normalizedUrl);
                                    let hostname = urlObj.hostname.replace('www.', '').toLowerCase();
                                    
                                    // Определяем соцсеть по домену
                                    let icon = '';
                                    let title = '';
                                    
                                    // Telegram
                                    if (hostname === 'telegram.org' || hostname === 't.me' || hostname.endsWith('.t.me')) {
                                        icon = '✈️';
                                        title = 'Telegram';
                                    } 
                                    // Instagram
                                    else if (hostname === 'instagram.com' || hostname.endsWith('.instagram.com')) {
                                        icon = '📷';
                                        title = 'Instagram';
                                    } 
                                    // VK
                                    else if (hostname === 'vk.com' || hostname === 'vkontakte.ru' || hostname.endsWith('.vk.com')) {
                                        icon = '🔵';
                                        title = 'VK';
                                    } 
                                    // YouTube
                                    else if (hostname === 'youtube.com' || hostname === 'youtu.be' || hostname.endsWith('.youtube.com')) {
                                        icon = '📺';
                                        title = 'YouTube';
                                    } 
                                    // TikTok
                                    else if (hostname === 'tiktok.com' || hostname.endsWith('.tiktok.com')) {
                                        icon = '🎵';
                                        title = 'TikTok';
                                    }
                                    
                                    if (icon) {
                                        iconContainer.innerHTML = `<span title="${title}">${icon}</span>`;
                                        iconContainer.classList.remove('hidden');
                                    } else {
                                        iconContainer.classList.add('hidden');
                                    }
                                } catch (e) {
                                    // Если URL невалидный, скрываем иконку
                                    iconContainer.classList.add('hidden');
                                }
                            }
                            
                            // Определяем соцсеть при загрузке страницы, если есть старое значение
                            document.addEventListener('DOMContentLoaded', function() {
                                const urlInput = document.getElementById('url');
                                if (urlInput.value) {
                                    detectSocialNetwork(urlInput.value);
                                }
                            });
                        </script>
                        
                        <div>
                            <label class="flex items-center gap-2">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                <span class="text-sm text-gray-700">✅ Ссылка активна (будет отображаться на публичной странице)</span>
                            </label>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                💾 Сохранить ссылку
                            </button>
                            
                            <button 
                                type="button"
                                onclick="window.location.replace('{{ route('links.index') }}')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                ← Назад к списку ссылок
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>