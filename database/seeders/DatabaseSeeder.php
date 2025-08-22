<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       User::factory()->create([
          'email' => 'systemadmin.email@loja.manager.com',
           'password' => bcrypt('Salj-1357'),
        ]);
    }
}
