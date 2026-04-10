<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function profile()
    {
        // Get current admin from session
        $admin = Admin::find(session('admin_id'));
        
        // If no admin in session, create default data
        if (!$admin) {
            $admin = new Admin();
            $admin->name = 'Super Admin';
            $admin->email = 'admin@aryameals.test';
            $admin->phone = '+91 98765 43210';
            $admin->role = 'Super Administrator';
        }
        
        // Get session data
        $sessionData = [
            'last_login' => now()->subHours(2),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device' => $this->getDeviceInfo(request()->userAgent()),
            'browser' => $this->getBrowserInfo(request()->userAgent()),
            'location' => 'Delhi, India', // You can use IP geolocation service here
        ];
        
        return view('admin.profile', compact('admin', 'sessionData'));
    }
    
    public function updateProfile(Request $request)
    {
        // Get current admin from session
        $admin = Admin::find(session('admin_id'));
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Session expired. Please login again.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|max:50',
        ]);
        
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
    
    public function updatePassword(Request $request)
    {
        $admin = Admin::find(session('admin_id'));
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Session expired. Please login again.');
        }
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!password_verify($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $admin->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('admin.profile')->with('success', 'Password updated successfully!');
    }
    
    public function uploadProfilePicture(Request $request)
    {
        $admin = Admin::find(session('admin_id'));
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Session expired. Please login again.');
        }
        
        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);
        
        // Delete old profile picture if exists
        if ($admin->profile_picture) {
            Storage::delete('public/profiles/' . $admin->profile_picture);
            $oldFilePath = public_path('storage/profiles/' . $admin->profile_picture);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        
        // Upload new profile picture
        $file = $request->file('profile_picture');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        
        // Store in both locations for compatibility
        $file->storeAs('public/profiles', $filename);
        $file->move(public_path('storage/profiles'), $filename);
        
        $admin->update(['profile_picture' => $filename]);
        
        return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully!');
    }
    
    public function logout()
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
    }
    
    private function getDeviceInfo($userAgent)
    {
        // Simple device detection
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows 10';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
            return 'iOS';
        }
        
        return 'Unknown Device';
    }
    
    private function getBrowserInfo($userAgent)
    {
        // Simple browser detection
        if (preg_match('/Chrome/i', $userAgent)) {
            preg_match('/Chrome\/(\d+)/i', $userAgent, $matches);
            return 'Chrome ' . ($matches[1] ?? 'Unknown');
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            preg_match('/Firefox\/(\d+)/i', $userAgent, $matches);
            return 'Firefox ' . ($matches[1] ?? 'Unknown');
        } elseif (preg_match('/Safari/i', $userAgent)) {
            preg_match('/Safari\/(\d+)/i', $userAgent, $matches);
            return 'Safari ' . ($matches[1] ?? 'Unknown');
        } elseif (preg_match('/Edge/i', $userAgent)) {
            preg_match('/Edge\/(\d+)/i', $userAgent, $matches);
            return 'Edge ' . ($matches[1] ?? 'Unknown');
        }
        
        return 'Unknown Browser';
    }
}
