@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Remarcar Consulta
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Alterar data e horário da consulta.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Paciente
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $consulta->paciente->nome }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Médico
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $consulta->medico->nome }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Data Atual
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <form action="{{ route('consultas.remarcacao.update', $consulta->id) }}" method="POST" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="data" class="block text-sm font-medium text-gray-700">Nova Data</label>
                        <div class="mt-1">
                            <input type="date" name="data" id="data" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="hora" class="block text-sm font-medium text-gray-700">Novo Horário</label>
                        <div class="mt-1">
                            <input type="time" name="hora" id="hora" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="plantao_id" class="block text-sm font-medium text-gray-700">Plantão</label>
                        <div class="mt-1">
                            <select id="plantao_id" name="plantao_id" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Selecione um plantão</option>
                                @foreach($plantoes as $plantao)
                                    <option value="{{ $plantao->id }}">
                                        {{ \Carbon\Carbon::parse($plantao->data_inicio)->format('d/m/Y') }} - 
                                        {{ $plantao->hora_inicio }} às {{ $plantao->hora_fim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="observacoes" class="block text-sm font-medium text-gray-700">Motivo da Remarcação</label>
                        <div class="mt-1">
                            <textarea id="observacoes" name="observacoes" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('consultas.show', $consulta->id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Salvar Remarcação
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
