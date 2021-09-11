<?php

namespace Database\Factories;

use App\Models\Listt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ListtFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => Str::random(8),
            "position" => 0
        ];
    }
}
