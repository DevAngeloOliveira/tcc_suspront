<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Exame;
use App\Models\Consulta;
use App\Models\Prontuario;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExameControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup para os testes.
     */
    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Teste de listagem de exames.
     */
    public function test_index_displays_exames()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Exame',
            'cpf' => '123.456.789-00',
            'cartao_sus' => '123456789012345',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Exame, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'paciente.exame@example.com'
        ]);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Exame',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '987.654.321-00',
            'telefone' => '(11) 88888-7777',
            'email' => 'dr.exame@example.com',
            'user_id' => $user->id,
        ]);

        // Criar um exame
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Hemograma',
            'descricao' => 'Exame de sangue completo',
            'data_solicitacao' => Carbon::today(),
            'status' => 'solicitado'
        ]);

        // Acessar a página de listagem de exames
        $response = $this->actingAs($user)->get(route('exames.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o exame está presente na listagem
        $response->assertSee('Paciente Exame');
        $response->assertSee('Hemograma');
        $response->assertSee('Dr. Exame');
    }

    /**
     * Teste de criação de exame.
     */
    public function test_store_creates_new_exame()
    {
        // Criar um usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar um paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Criar Exame',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Rua Criar Exame, 456',
            'telefone' => '(11) 97777-6666',
            'email' => 'paciente.criar.exame@example.com'
        ]);

        // Criar prontuário
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id
        ]);

        // Criar um médico associado ao usuário médico
        $medico = Medico::create([
            'nome' => 'Dr. Criar Exame',
            'crm' => '67890SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 96666-5555',
            'email' => 'dr.criar.exame@example.com',
            'user_id' => $userMedico->id,
        ]);

        // Criar uma consulta
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::now()->subDay(),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Dor no peito',
            'status' => 'concluida'
        ]);

        // Dados para criar um novo exame
        $exameData = [
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'consulta_id' => $consulta->id,
            'tipo_exame' => 'Eletrocardiograma',
            'descricao' => 'Avaliação cardíaca completa',
            'data_solicitacao' => now()->format('Y-m-d'),
            'status' => 'solicitado'
        ];

        // Enviar requisição POST para criar exame
        $response = $this->actingAs($userMedico)->post(route('exames.store'), $exameData);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se o exame foi criado no banco de dados
        $this->assertDatabaseHas('exames', [
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Eletrocardiograma',
            'descricao' => 'Avaliação cardíaca completa',
            'status' => 'solicitado'
        ]);
    }

    /**
     * Teste de exibição de detalhes de um exame.
     */
    public function test_show_displays_exame()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e médico
        $paciente = Paciente::create([
            'nome' => 'Paciente Detalhes Exame',
            'cpf' => '123.123.123-12',
            'cartao_sus' => '123123123123123',
            'data_nascimento' => '1975-10-25',
            'sexo' => 'M',
            'endereco' => 'Rua Detalhes Exame, 789',
            'telefone' => '(11) 95555-4444',
            'email' => 'paciente.detalhes.exame@example.com'
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Detalhes Exame',
            'crm' => '13579SP',
            'especialidade' => 'Neurologia',
            'cpf' => '321.321.321-32',
            'telefone' => '(11) 94444-3333',
            'email' => 'dr.detalhes.exame@example.com',
            'user_id' => $user->id,
        ]);

        // Criar um exame
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Ressonância Magnética',
            'descricao' => 'Ressonância da coluna lombar',
            'data_solicitacao' => Carbon::today(),
            'status' => 'solicitado'
        ]);

        // Acessar página de detalhes do exame
        $response = $this->actingAs($user)->get(route('exames.show', $exame->id));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se os detalhes do exame são exibidos
        $response->assertSee('Paciente Detalhes Exame');
        $response->assertSee('Dr. Detalhes Exame');
        $response->assertSee('Ressonância Magnética');
        $response->assertSee('Ressonância da coluna lombar');
    }

    /**
     * Teste de atualização de exame.
     */
    public function test_update_exame()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e médico
        $paciente = Paciente::create([
            'nome' => 'Paciente Atualizar Exame',
            'cpf' => '444.444.444-44',
            'cartao_sus' => '444444444444444',
            'data_nascimento' => '1980-07-20',
            'sexo' => 'F',
            'endereco' => 'Rua Atualizar Exame, 100',
            'telefone' => '(11) 93333-2222',
            'email' => 'paciente.atualizar.exame@example.com'
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Atualizar Exame',
            'crm' => '24680SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '444.333.222-11',
            'telefone' => '(11) 92222-1111',
            'email' => 'dr.atualizar.exame@example.com',
            'user_id' => $user->id,
        ]);

        // Criar exame
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Exame Original',
            'descricao' => 'Descrição original',
            'data_solicitacao' => Carbon::today(),
            'status' => 'solicitado'
        ]);

        // Criar arquivo para upload
        $file = UploadedFile::fake()->create('resultado.pdf', 500);

        // Dados atualizados
        $dadosAtualizados = [
            'tipo_exame' => 'Exame Original', // Campo obrigatório
            'data_solicitacao' => Carbon::today()->format('Y-m-d'), // Campo obrigatório
            'status' => 'realizado',
            'resultado' => 'Resultado do exame atualizado',
            'arquivo_resultado' => $file,
            'data_realizacao' => Carbon::today()->format('Y-m-d'),
        ];

        // Enviar requisição PUT para atualizar o exame
        $response = $this->actingAs($user)->put(route('exames.update', $exame->id), $dadosAtualizados);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se os dados foram atualizados no banco de dados
        $this->assertDatabaseHas('exames', [
            'id' => $exame->id,
            'status' => 'realizado',
            'resultado' => 'Resultado do exame atualizado',
        ]);

        // Verificar que o caminho do arquivo foi salvo no banco
        $exameAtualizado = Exame::find($exame->id);
        $this->assertNotNull($exameAtualizado->arquivo_resultado_path);

        // Verificar que o arquivo foi armazenado
        Storage::disk('public')->assertExists(str_replace('storage/', '', $exameAtualizado->arquivo_resultado_path));
    }

    /**
     * Teste de exclusão de exame.
     */
    public function test_delete_exame()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e médico
        $paciente = Paciente::create([
            'nome' => 'Paciente Excluir Exame',
            'cpf' => '999.888.777-66',
            'cartao_sus' => '999888777666555',
            'data_nascimento' => '1995-12-25',
            'sexo' => 'F',
            'endereco' => 'Rua Excluir Exame, 300',
            'telefone' => '(11) 91111-0000',
            'email' => 'paciente.excluir.exame@example.com'
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Excluir Exame',
            'crm' => '99999SP',
            'especialidade' => 'Dermatologia',
            'cpf' => '111.222.333-00',
            'telefone' => '(11) 90000-0000',
            'email' => 'dr.excluir.exame@example.com',
            'user_id' => $user->id,
        ]);

        // Criar exame
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Biópsia de Pele',
            'descricao' => 'Análise de lesão cutânea',
            'data_solicitacao' => Carbon::today(),
            'status' => 'solicitado'
        ]);

        // Enviar requisição DELETE para excluir o exame
        $response = $this->actingAs($user)->delete(route('exames.destroy', $exame->id));

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar soft delete - o exame deve ter deleted_at preenchido
        $this->assertSoftDeleted('exames', ['id' => $exame->id]);
    }

    /**
     * Teste de upload de resultados de exame.
     */
    public function test_upload_resultado_exame()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e médico
        $paciente = Paciente::create([
            'nome' => 'Paciente Upload',
            'cpf' => '777.777.777-77',
            'cartao_sus' => '777777777777777',
            'data_nascimento' => '1990-03-15',
            'sexo' => 'M',
            'endereco' => 'Rua Upload, 500',
            'telefone' => '(11) 97777-7777',
            'email' => 'paciente.upload@example.com'
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Upload',
            'crm' => '77777SP',
            'especialidade' => 'Radiologia',
            'cpf' => '777.888.999-00',
            'telefone' => '(11) 97777-0000',
            'email' => 'dr.upload@example.com',
            'user_id' => $user->id,
        ]);

        // Criar exame
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_exame' => 'Raio-X',
            'descricao' => 'Raio-X de tórax',
            'data_solicitacao' => Carbon::today()->subDays(5),
            'status' => 'solicitado'
        ]);

        // Criar arquivo para upload
        $file = UploadedFile::fake()->create('raio-x.pdf', 1024);

        // Dados para atualizar o exame
        $dadosUpload = [
            'status' => 'realizado',
            'resultado' => 'Sem alterações significativas',
            'arquivo_resultado' => $file,
            'data_realizacao' => Carbon::today()->format('Y-m-d'),
            'tipo_exame' => 'Raio-X',
            'data_solicitacao' => Carbon::today()->subDays(5)->format('Y-m-d'),
        ];

        // Enviar requisição PUT para atualizar o exame
        $response = $this->actingAs($user)->put(route('exames.update', $exame->id), $dadosUpload);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se os dados foram atualizados no banco de dados
        $exameAtualizado = Exame::find($exame->id);
        $this->assertEquals('realizado', $exameAtualizado->status);
        $this->assertEquals('Sem alterações significativas', $exameAtualizado->resultado);
        $this->assertNotNull($exameAtualizado->arquivo_resultado_path);
        $this->assertNotNull($exameAtualizado->data_realizacao);

        // Verificar que o arquivo foi armazenado
        Storage::disk('public')->assertExists(str_replace('storage/', '', $exameAtualizado->arquivo_resultado_path));
    }

    /**
     * Teste de restrição de acesso para médicos.
     */
    public function test_medico_can_only_see_own_exames()
    {
        // Criar usuários médicos
        $medicoUser1 = User::factory()->create(['tipo' => 'medico']);
        $medicoUser2 = User::factory()->create(['tipo' => 'medico']);

        // Criar médicos
        $medico1 = Medico::create([
            'nome' => 'Dr. Autorizado Exame',
            'crm' => '11111SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '111.111.111-11',
            'telefone' => '(11) 91111-1111',
            'email' => 'dr.autorizado.exame@example.com',
            'user_id' => $medicoUser1->id
        ]);

        $medico2 = Medico::create([
            'nome' => 'Dr. Não Autorizado Exame',
            'crm' => '22222SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '222.222.222-22',
            'telefone' => '(11) 92222-2222',
            'email' => 'dr.nao.autorizado.exame@example.com',
            'user_id' => $medicoUser2->id
        ]);

        // Criar paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Restrição Exame',
            'cpf' => '888.888.888-88',
            'cartao_sus' => '888888888888888',
            'data_nascimento' => '1990-08-08',
            'sexo' => 'F',
            'endereco' => 'Rua Restrição Exame, 800',
            'telefone' => '(11) 98888-8888',
            'email' => 'paciente.restricao.exame@example.com'
        ]);

        // Criar exame solicitado pelo primeiro médico
        $exame = Exame::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico1->id,
            'tipo_exame' => 'Hemograma',
            'descricao' => 'Exame de sangue completo',
            'data_solicitacao' => Carbon::today(),
            'status' => 'solicitado'
        ]);

        // Médico autorizado deve conseguir ver o exame na listagem
        $response = $this->actingAs($medicoUser1)->get(route('exames.index'));
        $response->assertStatus(200);
        $response->assertSee('Hemograma');

        // Médico não autorizado não deve ver o exame na listagem
        $response = $this->actingAs($medicoUser2)->get(route('exames.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Hemograma');
    }
}
