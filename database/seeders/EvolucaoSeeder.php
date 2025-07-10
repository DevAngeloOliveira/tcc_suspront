<?php

namespace Database\Seeders;

use App\Models\Evolucao;
use App\Models\Medico;
use App\Models\Prontuario;
use Illuminate\Database\Seeder;

class EvolucaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter todos os prontuários
        $prontuarios = Prontuario::all();

        // Obter todos os médicos
        $medicos = Medico::all();

        if ($prontuarios->count() == 0 || $medicos->count() == 0) {
            return;
        }

        // Para cada prontuário, criar algumas evoluções
        foreach ($prontuarios as $prontuario) {
            // Número aleatório de evoluções entre 0 e 4
            $qtdEvolucoes = rand(0, 4);

            for ($i = 0; $i < $qtdEvolucoes; $i++) {
                // Seleciona um médico aleatório
                $medico = $medicos->random();

                // Cria uma evolução com data entre 30 dias atrás e hoje
                $dataEvolucao = now()->subDays(rand(0, 30));

                Evolucao::create([
                    'prontuario_id' => $prontuario->id,
                    'medico_id' => $medico->id,
                    'descricao' => $this->getRandomEvolucao(),
                    'created_at' => $dataEvolucao,
                    'updated_at' => $dataEvolucao
                ]);
            }
        }
    }

    /**
     * Retorna uma descrição aleatória para evolução
     */
    private function getRandomEvolucao(): string
    {
        $evolucoes = [
            'Paciente apresenta melhora significativa após início do tratamento com antibióticos.',
            'Sintomas persistem, necessária alteração na posologia da medicação.',
            'Exames laboratoriais indicam normalização dos valores. Paciente assintomático.',
            'Quadro clínico estável. Mantida a medicação prescrita anteriormente.',
            'Paciente relata dor na região abdominal. Solicitados exames complementares.',
            'Ferida operatória com boa cicatrização, sem sinais de infecção.',
            'Pressão arterial controlada. Orientado quanto à dieta e atividade física.',
            'Glicemia de jejum elevada. Ajustada dose de insulina e reforçadas orientações nutricionais.',
            'Resultados dos exames indicam anemia. Prescrita suplementação de ferro.',
            'Avaliação pós-operatória satisfatória. Alta médica concedida.',
        ];

        return $evolucoes[array_rand($evolucoes)];
    }
}
