<?php

namespace App\Livewire\Consultas;

use App\Models\Consulta;
use App\Models\Medico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultaList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtros
    public $search = '';
    public $status = '';
    public $data = '';
    public $medicoId = '';

    // Confirmação de exclusão
    public $confirmarCancelamento = false;
    public $consultaParaCancelar = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'data' => ['except' => ''],
        'medicoId' => ['except' => ''],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->status = request('status', '');
        $this->data = request('data', '');
        $this->medicoId = request('medico_id', '');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingData()
    {
        $this->resetPage();
    }

    public function updatingMedicoId()
    {
        $this->resetPage();
    }

    public function limparFiltros()
    {
        $this->reset(['search', 'status', 'data', 'medicoId']);
        $this->resetPage();
    }

    public function confirmarCancelamento($id)
    {
        $this->consultaParaCancelar = Consulta::findOrFail($id);
        $this->confirmarCancelamento = true;
    }

    public function cancelarConsulta()
    {
        if ($this->consultaParaCancelar) {
            $this->consultaParaCancelar->status = 'cancelada';
            $this->consultaParaCancelar->save();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Consulta cancelada com sucesso!'
            ]);

            $this->confirmarCancelamento = false;
            $this->consultaParaCancelar = null;
        }
    }

    public function iniciarAtendimento($id)
    {
        $consulta = Consulta::findOrFail($id);
        $consulta->status = 'em_andamento';
        $consulta->save();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Atendimento iniciado com sucesso!'
        ]);
    }

    public function getMedicosProperty()
    {
        if (Auth::user()->tipo == 'medico') {
            return [];
        }

        return Medico::orderBy('nome')->get();
    }

    public function render()
    {
        $query = Consulta::with(['paciente', 'medico']);

        // Filtro por status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Filtro por data
        if ($this->data) {
            $query->whereDate('data_hora', $this->data);
        }

        // Filtro por médico
        if ($this->medicoId) {
            $query->where('medico_id', $this->medicoId);
        } elseif (Auth::user()->tipo == 'medico') {
            // Se for médico, mostrar apenas suas consultas
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Busca por termo (nome do paciente ou médico)
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('paciente', function ($q2) {
                    $q2->where('nome', 'like', '%' . $this->search . '%');
                })->orWhereHas('medico', function ($q2) {
                    $q2->where('nome', 'like', '%' . $this->search . '%');
                });
            });
        }

        $consultas = $query->orderBy('data_hora', 'desc')->paginate(10);

        return view('livewire.consultas.consulta-list', [
            'consultas' => $consultas
        ]);
    }
}
