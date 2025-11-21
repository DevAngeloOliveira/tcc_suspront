@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Minhas Notificações</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($notificacoes->count() > 0)
            <div class="p-4 border-b border-gray-200 flex justify-end">
                <form action="{{ route('notificacoes.marcar-todas-como-lidas') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-900">
                        Marcar todas como lidas
                    </button>
                </form>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($notificacoes as $notificacao)
                    <li class="{{ $notificacao->lida ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    {{ $notificacao->titulo }}
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $notificacao->lida ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $notificacao->lida ? 'Lida' : 'Nova' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        {{ $notificacao->mensagem }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        {{ $notificacao->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @if(!$notificacao->lida)
                                <div class="mt-2 flex justify-end">
                                    <form action="{{ route('notificacoes.marcar-como-lida', $notificacao->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-900">
                                            Marcar como lida
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="p-4">
                {{ $notificacoes->links() }}
            </div>
        @else
            <div class="p-4 text-center text-gray-500">
                Nenhuma notificação encontrada.
            </div>
        @endif
    </div>
</div>
@endsection
