<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Мои ссылки') }}
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
                    {{-- 📝 Показываем сообщения об успехе/ошибке --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($links->count() > 0)
                        <div class="space-y-4">
                            @foreach($links as $link)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <strong class="text-lg">{{ $link->title }}</strong>
                                            @if($link->is_active)
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">✅ Активна</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded">❌ Неактивна</span>
                                            @endif
                                        </div>
                                        <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                            {{ $link->url }}
                                        </a>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 ml-4">
                                        {{-- 📝 Кнопка редактирования --}}
                                        <a href="{{ route('links.edit', $link) }}" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded">
                                            ✏️
                                        </a>
                                        
                                        {{-- 📝 Форма для удаления --}}
                                        <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Удалить эту ссылку?')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">😔 У вас пока нет ссылок</p>
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <a href="{{ route('links.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ➕ Добавить новую ссылку
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>