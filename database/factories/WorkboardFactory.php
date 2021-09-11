<?php

namespace Database\Factories;

use App\Models\Workboard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkboardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workboard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => Str::random(12)
        ];
    }
}
