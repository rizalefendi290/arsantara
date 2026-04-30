<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'password' => Hash::make(Str::random(16)), // 🔥 WAJIB
                'role' => 'user',      // 🔥 default role
                'status' => 'normal',  // 🔥 default status
            ]
        );

        // 🔥 FIX: isi jika null
        if (!$user->role) {
            $user->role = 'user';
        }

        if (!$user->status) {
            $user->status = 'normal';
        }

        $user->name = $googleUser->name; // optional update name
        $user->save();

        Auth::login($user);

        return redirect('/'); // atau ke dashboard
    }


}

