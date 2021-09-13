<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Listt;
use App\Models\User;
use App\Models\Workboard;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->has(Workboard::factory()
                ->has(Listt::factory(3)
                    ->has(Card::factory(7))
                )
            )
            ->create();
    }
}
