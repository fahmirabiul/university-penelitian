<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;

class SsoAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('sso')->redirect();
    }

    public function callback()
    {
        try {
            $ssoUser = Socialite::driver('sso')->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SSO Login Error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect('/')->with('error', 'Gagal terhubung ke SSO: ' . $e->getMessage());
        }

        // Cache the raw SSO payload to serve as the single source of truth for user roles.
        Cache::put('sso_profile_' . $ssoUser->getId(), $ssoUser->user, now()->addHours(2));

        $ssoRoles = collect($ssoUser->user['roles'] ?? [])->pluck('name')->toArray();
        $roleLokal = 'dosen'; // Default
        
        if (in_array('admin_unit', $ssoRoles) || in_array('admin', $ssoRoles)) {
            $roleLokal = 'admin_lembaga';
        } elseif (in_array('dosen', $ssoRoles)) {
            $roleLokal = 'dosen';
        } elseif (in_array('mahasiswa', $ssoRoles)) {
            $roleLokal = 'mahasiswa';
        }

        $localUser = User::firstOrCreate(
            ['sso_id' => $ssoUser->getId()],
            [
                'name' => $ssoUser->getName(),
                'email' => $ssoUser->getEmail(),
                'role_lokal' => $roleLokal,
            ]
        );

        if ($localUser->name !== $ssoUser->getName() || $localUser->email !== $ssoUser->getEmail() || $localUser->role_lokal !== $roleLokal) {
            $localUser->update([
                'name' => $ssoUser->getName(),
                'email' => $ssoUser->getEmail(),
                'role_lokal' => $roleLokal,
            ]);
        }

        Auth::login($localUser);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Cache::forget('sso_profile_' . Auth::user()->sso_id);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }
}
