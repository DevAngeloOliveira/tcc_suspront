<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medico;
use App\Models\Atendente;
use App\Models\Paciente;
use App\Models\Prontuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        $adminUser = User::create([
            'name' => 'Admin SusPront',
            'email' => 'admin@suspront.com',
            'password' => Hash::make('senha123'),
            'tipo' => 'admin',
        ]);

        // Criar usuário médico
        $medicoUser = User::create([
            'name' => 'Dr. João Silva',
            'email' => 'medico@suspront.com',
            'password' => Hash::make('senha123'),
            'tipo' => 'medico',
        ]);

        // Criar perfil do médico
        Medico::create([
            'nome' => 'Dr. Chico Oliveira',
            'crm' => '12345-SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '123.456.789-00',
            'telefone' => '(11) 98765-4321',
            'email' => 'medico@suspront.com',
            'user_id' => $medicoUser->id,
        ]);

        // Criar usuário atendente
        $atendenteUser = User::create([
            'name' => 'Maria Oliveira',
            'email' => 'atendente@suspront.com',
            'password' => Hash::make('senha123'),
            'tipo' => 'atendente',
        ]);

        // Criar perfil do atendente
        Atendente::create([
            'nome' => 'Maria Oliveira',
            'cpf' => '987.654.321-00',
            'telefone' => '(11) 91234-5678',
            'registro' => 'REG123',
            'email' => 'atendente@suspront.com',
            'user_id' => $atendenteUser->id,
        ]);

        // Criar paciente genérico e seu prontuário
        $paciente = Paciente::create([
            'nome' => 'José Santos',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '123456789012345',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Principal, 123',
            'telefone' => '(11) 97777-8888',
            'email' => 'paciente@email.com'
        ]);

        // Criar prontuário do paciente
        Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico médico do paciente',
            'medicamentos_atuais' => 'Nenhum medicamento em uso',
            'observacoes' => 'Paciente em bom estado geral'
        ]);
    }
}
