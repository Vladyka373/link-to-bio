<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- 📝 Публичная ссылка -->
        <div x-data="{ copied: false }">
            <x-input-label for="public_url" :value="__('Публичная ссылка')" />
            <div class="mt-1 flex gap-2">
                <x-text-input 
                    id="public_url" 
                    type="text" 
                    readonly
                    class="flex-1" 
                    :value="url('/@' . $user->name)" 
                    onclick="this.select()"
                />
                <button 
                    type="button"
                    @click="
                        navigator.clipboard.writeText('{{ url('/@' . $user->name) }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <span x-show="!copied">📋 Копировать</span>
                    <span x-show="copied" x-transition>✅ Скопировано!</span>
                </button>
            </div>
            <p class="mt-1 text-sm text-gray-500">Поделитесь этой ссылкой, чтобы другие могли увидеть ваш профиль</p>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- 📝 Поле Bio -->
        <div>
            <x-input-label for="bio" :value="__('О себе')" />
            <textarea 
                id="bio" 
                name="bio" 
                rows="3" 
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            >{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- 📝 Поле Avatar -->
        <div>
            <x-input-label for="avatar" :value="__('Аватар')" />
            
            {{-- 📝 Показываем текущий аватар, если он есть (из БД) --}}
            @if($user->avatar)
                <div class="mt-2 mb-4">
                    <img 
                        src="{{ strpos($user->avatar, 'data:') === 0 ? $user->avatar : route('profile.avatar', $user->id) }}" 
                        alt="Текущий аватар" 
                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold" style="display: none;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
            @endif
            
            <input 
                id="avatar" 
                name="avatar" 
                type="file" 
                accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100"
            />
            <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF до 2MB</p>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- 📝 Поле Цвет темы -->
        <div>
            <x-input-label for="theme_color" :value="__('Цвет темы')" />
            <div class="mt-1">
                <input 
                    type="color" 
                    id="theme_color" 
                    name="theme_color" 
                    value="{{ old('theme_color', $user->theme_color ?? '#007bff') }}"
                    class="h-10 w-20 rounded border-gray-300 cursor-pointer"
                />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('theme_color')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
