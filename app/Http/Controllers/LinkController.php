<?php

namespace App\Http\Controllers;
use App\Models\Link;  // 📝 Импортируем модель Link
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = auth()->user()->links; // 📝 Получаем ссылки текущего пользователя
        return view('links.index', compact('links')); // 📝 Передаем ссылки в представление
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 📝 Просто возвращаем представление с формой
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // 📝 Гибкая валидация для ЛЮБЫХ ссылок
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'url' => [
            'required',
            'string',
            'max:1000',
            function ($attribute, $value, $fail) {
                // 📝 Проверяем что это похоже на URL (с http/https или без)
                $pattern = '/^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\-\.\/?%&=]*)?$/';
                if (!preg_match($pattern, $value)) {
                    $fail('Поле :attribute должно быть валидной ссылкой.');
                }
            },
        ],
        'is_active' => 'nullable|boolean',
    ]);

    try {
        // 📝 Автоматически добавляем https:// если нет протокола
        $url = $validated['url'];
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        // 📝 Создаем новую ссылку используя массовое присвоение
        $link = Link::create([
            'title' => $validated['title'],
            'url' => $url,  // 📝 Сохраняем с https://
            'user_id' => auth()->id(),
            'is_active' => $request->has('is_active') && $request->is_active == '1',
        ]);

        return redirect()->route('links.index')
                        ->with('success', '✅ Ссылка успешно добавлена!');
                        
    } catch (\Exception $e) {
        return back()->with('error', '❌ Ошибка при сохранении ссылки: ' . $e->getMessage());
    }
    }

    /**
 * 📝 Показать публичный профиль пользователя
 */
public function show($username)
{
    // 📝 Найти пользователя по имени
    $user = User::where('name', $username)->firstOrFail();
    
    // 📝 Получить ВСЕ ссылки этого пользователя
    $links = $user->links;
    
    // 📝 Показать публичную страницу
    return view('profile.public', compact('user', 'links'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        // 📝 Проверяем что пользователь редактирует свою ссылку
        if ($link->user_id !== auth()->id()) {
            abort(403); // 📝 Запрещаем редактирование чужих ссылок
        }
    
        return view('links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        // 📝 Проверяем что пользователь обновляет свою ссылку
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

    // 📝 Та же валидация что и при создании
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'url' => [
            'required',
            'string',
            'max:1000',
            function ($attribute, $value, $fail) {
                $pattern = '/^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\-\.\/?%&=]*)?$/';
                if (!preg_match($pattern, $value)) {
                    $fail('Поле :attribute должно быть валидной ссылкой.');
                }
            },
        ],
        'is_active' => 'nullable|boolean',
    ]);

    try {
        // 📝 Автоматически добавляем https:// если нет протокола
        $url = $validated['url'];
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        // 📝 Обновляем ссылку используя массовое присвоение
        $link->update([
            'title' => $validated['title'],
            'url' => $url,
            'is_active' => $request->has('is_active') && $request->is_active == '1',
        ]);

        return redirect()->route('links.index')
                        ->with('success', '✅ Ссылка успешно обновлена!');
                        
    } catch (\Exception $e) {
        return back()->with('error', '❌ Ошибка при обновлении ссылки: ' . $e->getMessage());
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        // 📝 Проверяем что пользователь удаляет свою ссылку
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }
        
        try {
            $link->delete();
            return redirect()->route('links.index')
                            ->with('success', '✅ Ссылка успешно удалена!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Ошибка при удалении ссылки: ' . $e->getMessage());
        }
    }
}
