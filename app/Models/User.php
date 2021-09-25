<?php

namespace App\Models;

use App\Http\Controllers\ProfileController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->workboards()->create([
                "name" => $user->email . "'s workboard"
            ]);

            $user->profile()->create([
                "profile_pic_color" => ProfileController::generateRandomColor(),
                "profile_pic_initials" => strtoupper(substr($user->email,0,2))
            ]);
        });

    }

    function workboards() {
        return $this->hasMany(Workboard::class);
    }

    function joinedWorkboards() {
        return $this->belongsToMany(Workboard::class, 'users_workboards');
    }

    function profile() {
        return $this->hasOne(Profile::class);
    }
}
