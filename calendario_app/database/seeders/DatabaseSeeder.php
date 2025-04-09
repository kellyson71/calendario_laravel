<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Criar o usuário padrão
        User::create([
            'name' => 'Kellyson',
            'username' => 'kellyson',
            'email' => 'kellyson@example.com',
            'password' => Hash::make('kellyson'),
        ]);

        // Executar o seeder de agendamentos
        $this->call(AgendamentoSeeder::class);
    }
}
