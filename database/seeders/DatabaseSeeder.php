<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Se o ambiente for 'production', não faça nada e saia do método.
        if (app()->isProduction()) {
            return;
        }

        // Este código só será executado em ambientes de desenvolvimento/local.
        User::factory()->create([
            'email' => 'systemadmin.email@loja.manager.com',
            'password' => bcrypt('Salj-1357'),
        ]);
    }
}
