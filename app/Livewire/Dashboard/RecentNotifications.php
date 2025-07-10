<?php

namespace App\Livewire\Dashboard;

use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecentNotifications extends Component
{
    public function render()
    {
        $notificacoes = Notificacao::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        return view('livewire.dashboard.recent-notifications', compact('notificacoes'));
    }
}
