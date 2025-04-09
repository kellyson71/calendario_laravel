<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgendamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar alguns agendamentos de exemplo
        $agendamentos = [
            [
                'nome' => 'Consulta Médica',
                'data' => '2025-05-10',
                'hora_inicio' => '14:00:00',
                'hora_fim' => '15:00:00',
                'descricao' => 'Consulta com Dr. Silva',
                'status' => 'agendado'
            ],
            [
                'nome' => 'Reunião de Projeto',
                'data' => '2025-05-12',
                'hora_inicio' => '10:00:00',
                'hora_fim' => '11:30:00',
                'descricao' => 'Discussão sobre o novo projeto',
                'status' => 'agendado'
            ],
            [
                'nome' => 'Entrevista de Emprego',
                'data' => '2025-05-15',
                'hora_inicio' => '09:00:00',
                'hora_fim' => '10:00:00',
                'descricao' => 'Entrevista para vaga de desenvolvedor',
                'status' => 'confirmado'
            ]
        ];

        foreach ($agendamentos as $agendamento) {
            Agendamento::create($agendamento);
        }
    }
}
