<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // Название ссылки ("Мой Instagram")
            $table->string('url');             // Сама ссылка ("https://instagram.com/username")
            $table->foreignId('user_id')       // Кто создал ссылку
                  ->constrained()              // Связь с таблицей users
                  ->onDelete('cascade');       // Удалить ссылки если удален пользователь
            $table->timestamps();              // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
