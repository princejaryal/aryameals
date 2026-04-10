<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/profiles/' . $this->profile_picture);
        }
        
        // Generate avatar with initials
        $initials = strtoupper(substr($this->name ?? 'SA', 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=10b981&color=fff&size=200";
    }
}
