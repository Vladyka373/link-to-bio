<!DOCTYPE html>
<html>
<head>
    <title>Профиль {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .profile-header {
            margin-bottom: 30px;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f0f0f0;
            margin: 0 auto 15px;
            object-fit: cover;
            border: 3px solid {{ $user->theme_color ?? '#007bff' }};
        }
        .avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: {{ $user->theme_color ?? '#007bff' }};
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            border: 3px solid {{ $user->theme_color ?? '#007bff' }};
        }
        .bio {
            color: #666;
            margin: 10px 0;
            font-style: italic;
        }
        .links-list {
            list-style: none;
            padding: 0;
        }
        .link-item {
            margin: 10px 0;
        }
        .link-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: {{ $user->theme_color ?? '#007bff' }};
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: opacity 0.3s, transform 0.2s;
        }
        .link-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .social-icon {
            font-size: 24px;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="profile-header">
        {{-- 📝 Отображаем аватар из БД, если он загружен --}}
        @if($user->avatar)
            <img 
                src="{{ strpos($user->avatar, 'data:') === 0 ? $user->avatar : route('profile.avatar', $user->id) }}" 
                alt="{{ $user->name }}" 
                class="avatar" 
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="avatar-placeholder" style="display: none;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @else
            {{-- 📝 Если аватара нет, показываем инициалы --}}
            <div class="avatar-placeholder">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        
        <h1>{{ $user->name }}</h1>
        
        {{-- 📝 Отображаем bio, если оно указано --}}
        @if($user->bio)
            <p class="bio">{{ $user->bio }}</p>
        @else
            <p>Добро пожаловать на мою страницу!</p>
        @endif
    </div>

    <div class="links-section">
        <h2>Мои ссылки</h2>
        
        @if($links->count() > 0)
            <ul class="links-list">
                @foreach($links as $link)
                    <li class="link-item">
                        <a href="{{ $link->url }}" target="_blank" class="link-button">
                            @if($link->getSocialIcon())
                                <span class="social-icon">{{ $link->getSocialIcon() }}</span>
                            @endif
                            <span>{{ $link->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>😔 Пока нет ссылок</p>
        @endif
    </div>
</body>
</html>