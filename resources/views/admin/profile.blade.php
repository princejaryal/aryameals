@extends('admin.layouts.app')

@section('title', 'Profile - Arya Meals Admin')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-primary-50 to-primary-100 border border-primary-200 text-primary-700 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="mb-6 bg-gradient-to-r from-accent-red to-accent-red-dark/20 border border-accent-red/30 text-accent-red-dark px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- User Profile Card -->
    <div class="glass-effect rounded-2xl shadow-lg p-8 max-w-4xl mx-auto animate-fade-in">
        <!-- Profile Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">User Profile</h1>
            <div class="flex items-center space-x-3">
                <!-- Edit Profile Button -->
                <button onclick="showEditModal()" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg font-medium text-sm hover:from-purple-600 hover:to-pink-600 transition-all shadow-lg hover:scale-105 transform">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Profile
                </button>
                <!-- Upload Picture Button -->
                <button onclick="document.getElementById('profilePictureInput').click()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg font-medium text-sm hover:from-blue-600 hover:to-cyan-600 transition-all shadow-lg hover:scale-105 transform">
                    <i class="fas fa-camera mr-2"></i>
                    Change Photo
                </button>
            </div>
        </div>

        <!-- User Info Section -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Profile Picture -->
            <div class="flex flex-col items-center">
                <div class="relative">
                    <img src="{{ $admin->profile_picture_url }}" 
                         alt="Profile Picture" 
                         class="h-32 w-32 rounded-2xl shadow-xl border-4 border-white object-cover">
                    <div class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white font-bold shadow-lg cursor-pointer hover:scale-110 transition-transform animate-pulse"
                         onclick="document.getElementById('profilePictureInput').click()">
                        <i class="fas fa-camera text-sm"></i>
                    </div>
                </div>
                
                <!-- Hidden File Input -->
                <form action="{{ route('admin.upload.profile.picture') }}" method="POST" enctype="multipart/form-data" class="hidden" id="profilePictureForm">
                    @csrf
                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" onchange="this.form.submit()">
                </form>
                
                <!-- Basic User Info -->
                <div class="mt-6 text-center">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $admin->name }}</h2>
                    <p class="text-slate-500">{{ $admin->email }}</p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-gradient-to-r from-purple-50 to-pink-100 text-purple-700 px-3 py-1 text-sm font-semibold border border-purple-200 animate-pulse">
                        <span class="h-2 w-2 bg-purple-500 rounded-full mr-2"></span>
                        Active
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="flex-1 space-y-6">
                <!-- Login Information -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4 flex items-center">
                        <i class="fas fa-sign-in-alt mr-3 text-purple-600"></i>
                        Login Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-slate-200">
                            <span class="text-slate-600 font-medium">Last Login:</span>
                            <span class="text-slate-900 font-semibold">{{ $sessionData['last_login']->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-slate-200">
                            <span class="text-slate-600 font-medium">IP Address:</span>
                            <span class="text-slate-900 font-semibold font-mono">{{ $sessionData['ip_address'] }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <span class="text-slate-600 font-medium">Device:</span>
                            <span class="text-slate-900 font-semibold">{{ $sessionData['device'] }} • {{ $sessionData['browser'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-600"></i>
                        Account Details
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-blue-200">
                            <span class="text-slate-600 font-medium">Full Name:</span>
                            <span class="text-slate-900 font-semibold">{{ $admin->name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-blue-200">
                            <span class="text-slate-600 font-medium">Email:</span>
                            <span class="text-slate-900 font-semibold">{{ $admin->email }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-blue-200">
                            <span class="text-slate-600 font-medium">Phone:</span>
                            <span class="text-slate-900 font-semibold">{{ $admin->phone ?? '+91 98765 43210' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <span class="text-slate-600 font-medium">Role:</span>
                            <span class="text-slate-900 font-semibold">{{ $admin->role }}</span>
                        </div>
                    </div>
                </div>

                <!-- Session Information -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                        <i class="fas fa-clock mr-3 text-purple-600"></i>
                        Session Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-purple-200">
                            <span class="text-slate-600 font-medium">Session Start:</span>
                            <span class="text-slate-900 font-semibold">{{ $sessionData['last_login']->format('g:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <span class="text-slate-600 font-medium">Location:</span>
                            <span class="text-slate-900 font-semibold">{{ $sessionData['location'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <button onclick="showPasswordModal()" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                <i class="fas fa-key mr-2"></i>
                Change Password
            </button>
            <form action="{{ route('admin.logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-medium hover:from-red-600 hover:to-red-700 transition-all hover:scale-105 transform shadow-lg">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 max-w-md w-full mx-4 border border-purple-200 shadow-2xl animate-fade-in">
            <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-6">Edit Profile</h3>
            <form action="{{ route('admin.update.profile') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ $admin->name }}" required
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ $admin->email }}" required
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ $admin->phone ?? '' }}" 
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                        <select name="role" class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                            <option value="Super Administrator" {{ $admin->role == 'Super Administrator' ? 'selected' : '' }}>Super Administrator</option>
                            <option value="Administrator" {{ $admin->role == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="Manager" {{ $admin->role == 'Manager' ? 'selected' : '' }}>Manager</option>
                            <option value="Supervisor" {{ $admin->role == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg font-medium hover:from-purple-600 hover:to-pink-600 transition-all hover:scale-105 transform">
                        Save Changes
                    </button>
                    <button type="button" onclick="hideEditModal()" class="flex-1 px-4 py-3 bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-lg font-medium hover:from-slate-200 hover:to-slate-300 transition-all hover:scale-105 transform">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 max-w-md w-full mx-4 border border-purple-200 shadow-2xl animate-fade-in">
            <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-6">Change Password</h3>
            <form action="{{ route('admin.update.password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">New Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        Update Password
                    </button>
                    <button type="button" onclick="hidePasswordModal()" class="flex-1 px-4 py-3 bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-lg font-medium hover:from-slate-200 hover:to-slate-300 transition-all hover:scale-105 transform">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function showEditModal() {
            document.getElementById('editModal').style.display = 'flex';
        }
        
        function hideEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function showPasswordModal() {
            document.getElementById('passwordModal').style.display = 'flex';
        }
        
        function hidePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const passwordModal = document.getElementById('passwordModal');
            
            if (event.target == editModal) {
                hideEditModal();
            }
            if (event.target == passwordModal) {
                hidePasswordModal();
            }
        }
    </script>
@endsection
