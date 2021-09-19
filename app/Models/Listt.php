<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listt extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function($listt) {
            $listt->position = $listt->workboard->listts()->count();
            $listt->save();
        });

    }

    function workboard() {
        return $this->belongsTo(Workboard::class);
    }

    function cards() {
        return $this->hasMany(Card::class)->orderBy("position")->orderBy("title");
    }


}
