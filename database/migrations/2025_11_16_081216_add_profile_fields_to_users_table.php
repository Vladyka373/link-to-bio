<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->text('bio')->nullable();           // 📝 Описание профиля
        $table->string('avatar')->nullable();      // 📝 URL аватара
        $table->string('theme_color')->default('#007bff'); // 📝 Цвет темы
    });
}

public function down(): void
{
    // 📝 Проверяем наличие колонок перед удалением (для SQLite)
    if (Schema::hasColumn('users', 'bio')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
    }
    
    if (Schema::hasColumn('users', 'avatar')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
    
    if (Schema::hasColumn('users', 'theme_color')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('theme_color');
        });
    }
}
};
