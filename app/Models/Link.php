<?php

namespace App\Models;

// 📝 Импортируем необходимые классы
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    // 📝 Используем трейт HasFactory для создания фабрик
    use HasFactory;

    // 📝 Указываем какие поля можно массово заполнять
    // Это защита от массового присвоения (mass assignment)
    protected $fillable = [
        'title',    // Название ссылки можно заполнять
        'url',      // URL ссылки можно заполнять  
        'user_id',  // ID пользователя можно заполнять
        'is_active' // Активна ли ссылка
    ];

    // 📝 Метод для связи "ссылка принадлежит пользователю"
    public function user() {
        // belongsTo() - означает "принадлежит"
        // User::class - указывает на модель User
        return $this->belongsTo(User::class);
    }
    
    /**
     * 📝 Определить соцсеть по URL и вернуть иконку
     */
    public function getSocialIcon(): ?string
    {
        if (!$this->url) {
            return null;
        }
        
        try {
            // Нормализуем URL (добавляем протокол если нет)
            $url = strtolower($this->url);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = 'https://' . $url;
            }
            
            $hostname = parse_url($url, PHP_URL_HOST);
            if (!$hostname) {
                return null;
            }
            
            // Убираем www.
            $hostname = str_replace('www.', '', strtolower($hostname));
            
            // Определяем соцсеть по домену
            // Telegram
            if ($hostname === 'telegram.org' || $hostname === 't.me' || str_ends_with($hostname, '.t.me')) {
                return '✈️';
            }
            // Instagram
            elseif ($hostname === 'instagram.com' || str_ends_with($hostname, '.instagram.com')) {
                return '📷';
            }
            // VK
            elseif ($hostname === 'vk.com' || $hostname === 'vkontakte.ru' || str_ends_with($hostname, '.vk.com')) {
                return '🔵';
            }
            // YouTube
            elseif ($hostname === 'youtube.com' || $hostname === 'youtu.be' || str_ends_with($hostname, '.youtube.com')) {
                return '📺';
            }
            // TikTok
            elseif ($hostname === 'tiktok.com' || str_ends_with($hostname, '.tiktok.com')) {
                return '🎵';
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}