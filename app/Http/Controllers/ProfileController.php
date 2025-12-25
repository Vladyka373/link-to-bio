<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{



    /**
 * 📝 Показать публичный профиль пользователя
 */
public function show($username)
{
    // 📝 Найти пользователя по имени
    $user = User::where('name', $username)->firstOrFail();
    
    // 📝 Получить только активные ссылки этого пользователя
    $links = $user->links()->where('is_active', true)->get();
    
    
   // 📝 Показываем публичную страницу
   return view('profile.public', [
    'user' => $user,
    'links' => $links
]);
}


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 📝 Обрабатываем загрузку аватара - сохраняем в БД как base64
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $imageData = file_get_contents($file->getRealPath());
            $base64 = base64_encode($imageData);
            $mimeType = $file->getMimeType();
            
            // 📝 Сохраняем base64 с префиксом data URI для удобства
            $user->avatar = 'data:' . $mimeType . ';base64,' . $base64;
        }
        
        // 📝 Обновляем остальные поля
        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'theme_color' => $request->theme_color,
        ]);

        // 📝 Если изменился email, сбрасываем верификацию
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    /**
     * 📝 Отобразить аватар пользователя из БД
     */
    public function avatar($id)
    {
        $user = User::findOrFail($id);
        
        if (!$user->avatar) {
            abort(404);
        }
        
        // 📝 Извлекаем данные из base64
        if (strpos($user->avatar, 'data:') === 0) {
            // 📝 Формат: data:image/png;base64,...
            list($header, $data) = explode(',', $user->avatar, 2);
            $mimeType = explode(';', explode(':', $header)[1])[0];
            $imageData = base64_decode($data);
        } else {
            // 📝 Старый формат (просто base64)
            $imageData = base64_decode($user->avatar);
            $mimeType = 'image/jpeg'; // По умолчанию
        }
        
        return response($imageData)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
