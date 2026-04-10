<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Find existing user or create new one
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update existing user with Google data if they don't have google_id
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'google_token' => $googleUser->token,
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'google_token' => $googleUser->token,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)), // Random password
                ]);
            }
            
            // Login the user
            Auth::login($user);
            
            // Redirect to intended page or home
            return redirect()->route('home')->with('success', 'Successfully logged in with Google!');
            
        } catch (Exception $e) {
            // Log the error for debugging
            \Log::error('Google login error: ' . $e->getMessage());
            
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }
}
