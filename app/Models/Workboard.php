<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workboard extends Model
{
    use HasFactory;

    protected $guarded = [];

    function listts() {
        return $this->hasMany(Listt::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
