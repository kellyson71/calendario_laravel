# Exemplos de Templates PDF

## resources/views/appointments/pdf.blade.php
```php
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprovante de Agendamento</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        .content {
            margin: 0 auto;
            max-width: 600px;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 150px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0,0,0,0.1);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" class="logo" alt="Logo">
        <div class="title">Prefeitura Municipal de Pau dos Ferros</div>
        <div class="subtitle">Secretaria Municipal de Saúde</div>
    </div>

    <div class="content">
        <div class="info-box">
            <div class="info-row">
                <div class="info-label">Paciente:</div>
                <div class="info-value">{{ $appointment->patient_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data:</div>
                <div class="info-value">{{ $appointment->date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Horário:</div>
                <div class="info-value">{{ $appointment->time }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Médico:</div>
                <div class="info-value">{{ $appointment->doctor }}</div>
            </div>
            @if($appointment->notes)
            <div class="info-row">
                <div class="info-label">Observações:</div>
                <div class="info-value">{{ $appointment->notes }}</div>
            </div>
            @endif
        </div>

        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode(QrCode::size(200)->generate($appointment->id)) }}" alt="QR Code">
        </div>

        <div class="footer">
            <p>Este é um documento oficial da Prefeitura Municipal de Pau dos Ferros</p>
            <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Número do agendamento: {{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <div class="watermark">
        COMPROVANTE
    </div>
</body>
</html>
```

## resources/views/appointments/report.blade.php
```php
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Agendamentos</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .period {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" class="logo" alt="Logo">
        <div class="title">Relatório de Agendamentos</div>
        <div class="subtitle">Prefeitura Municipal de Pau dos Ferros - Secretaria de Saúde</div>
    </div>

    <div class="period">
        Período: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->date->format('d/m/Y') }}</td>
                <td>{{ $appointment->time }}</td>
                <td>{{ $appointment->patient_name }}</td>
                <td>{{ $appointment->doctor }}</td>
                <td>{{ $appointment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total de Agendamentos:</strong> {{ $appointments->count() }}</p>
        <p><strong>Agendados:</strong> {{ $appointments->where('status', 'agendado')->count() }}</p>
        <p><strong>Concluídos:</strong> {{ $appointments->where('status', 'concluído')->count() }}</p>
        <p><strong>Cancelados:</strong> {{ $appointments->where('status', 'cancelado')->count() }}</p>
    </div>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Página {PAGE_NUM} de {PAGE_COUNT}</p>
    </div>
</body>
</html>
``` 