<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function($card) {
            $card->position = $card->listt->cards()->count();
            $card->save();
        });

    }

    function listt() {
        return $this->belongsTo(Listt::class);
    }

}
