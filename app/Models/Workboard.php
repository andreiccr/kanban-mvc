<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workboard extends Model
{
    use HasFactory;

    protected $guarded = [];

    function listts() {
        return $this->hasMany(Listt::class)->orderBy("position")->orderBy("name");;
    }

    function user() {
        return $this->belongsTo(User::class);
    }

    function members() {
        return $this->belongsToMany(User::class, 'users_workboards')->withPivot("role");
    }
}
