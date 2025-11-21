<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receita Médica</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Receita Médica</h1>
        <p>Dr(a). {{ $receita->medico->nome }} - CRM: {{ $receita->medico->crm }}</p>
    </div>

    <div class="content">
        <p><strong>Paciente:</strong> {{ $receita->prontuario->paciente->nome }}</p>
        <p><strong>Data:</strong> {{ $receita->created_at->format('d/m/Y') }}</p>
        
        <h3>Medicamentos</h3>
        <p>{!! nl2br(e($receita->medicamentos)) !!}</p>

        <h3>Posologia</h3>
        <p>{!! nl2br(e($receita->posologia)) !!}</p>

        @if($receita->observacoes)
            <h3>Observações</h3>
            <p>{!! nl2br(e($receita->observacoes)) !!}</p>
        @endif
        
        <p><strong>Validade:</strong> {{ \Carbon\Carbon::parse($receita->validade)->format('d/m/Y') }}</p>
    </div>

    <div class="footer">
        <p>Assinatura do Médico</p>
    </div>
</body>
</html>
