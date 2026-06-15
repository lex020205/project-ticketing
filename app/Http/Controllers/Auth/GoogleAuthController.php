<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\RoleRedirectHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login dengan Google dibatalkan atau gagal. Silakan coba lagi.');
        }

        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->with('error', 'Tidak dapat mengambil email dari akun Google Anda.');
        }

        // Domain restriction check
        $allowedDomainsString = config('services.google.allowed_domain') ?: env('GOOGLE_ALLOWED_DOMAIN');
        if (!empty($allowedDomainsString)) {
            $emailParts = explode('@', $email);
            $emailDomain = end($emailParts);
            
            // Split by comma and trim whitespace
            $allowedDomains = array_map('trim', explode(',', $allowedDomainsString));
            
            $isAllowed = false;
            foreach ($allowedDomains as $allowedDomain) {
                if (strcasecmp($emailDomain, $allowedDomain) === 0) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                return redirect()->route('login')->with('error', 'Email dengan domain ini tidak diizinkan untuk login.');
            }
        }

        // Find user by google_id, fallback to email
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        // Only pre-registered users (by SPV) can login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Akun Google Anda belum terdaftar. Hubungi SPV untuk membuat akun.');
        }

        // Update Google OAuth fields if missing/changed
        $updated = false;
        if (empty($user->google_id)) {
            $user->google_id = $googleUser->getId();
            $updated = true;
        }
        if (empty($user->google_avatar) && $googleUser->getAvatar()) {
            $user->google_avatar = $googleUser->getAvatar();
            $updated = true;
        }
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $updated = true;
        }

        if ($updated) {
            $user->save();
        }

        // Check if user status is active
        if ($user->status_user === 'nonaktif') {
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi SPV.');
        }

        // Login user
        Auth::login($user, true);
        $request->session()->regenerate();

        return RoleRedirectHelper::redirectByRole($user->role?->nama_role);
    }
}
