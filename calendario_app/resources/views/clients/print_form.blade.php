<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Cliente - {{ $client->name }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .ficha-cliente {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .cabecalho {
            border-bottom: 2px solid #2c5282;
            margin-bottom: 20px;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            max-height: 80px;
        }

        .data-impressao {
            font-style: italic;
            font-size: 10px;
            color: #718096;
            text-align: right;
            margin-top: 5px;
        }

        .info-pessoal {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 15px;
        }

        .info-item {
            width: 33.33%;
            padding: 5px;
        }

        .info-completa {
            width: 100%;
            padding: 5px;
        }

        .label {
            font-weight: bold;
            font-size: 11px;
            color: #4a5568;
        }

        .table {
            font-size: 10px;
        }

        .observacoes {
            border: 1px dashed #e2e8f0;
            padding: 10px;
            margin-top: 15px;
            background-color: #f8fafc;
        }

        .historico-titulo {
            background-color: #edf2f7;
            padding: 5px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .assinatura {
            margin-top: 30px;
            text-align: center;
        }

        .linha-assinatura {
            width: 80%;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="no-print mb-3 mt-3">
            <button onclick="window.print()" class="btn btn-primary mb-3">
                <i class="fas fa-print"></i> Imprimir Ficha
            </button>
            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="ficha-cliente">
            <div class="cabecalho">
                <div>
                    <img src="{{ asset('img/ficha_logo.png') }}" alt="Logo" class="logo">
                </div>
                <div class="text-end">
                    <h2>FICHA DE ATENDIMENTO</h2>
                    <div class="data-impressao">
                        Gerada em: {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <div class="info-pessoal">
                <div class="info-item">
                    <div class="label">Nome Completo:</div>
                    <div>{{ $client->name }}</div>
                </div>

                <div class="info-item">
                    <div class="label">Telefone:</div>
                    <div>{{ $client->phone ?? 'Não informado' }}</div>
                </div>

                <div class="info-item">
                    <div class="label">E-mail:</div>
                    <div>{{ $client->email ?? 'Não informado' }}</div>
                </div>

                <div class="info-item">
                    <div class="label">Data de Nascimento:</div>
                    <div>
                        @if($client->birth_date)
                        {{ $client->birth_date->format('d/m/Y') }} ({{ $client->birth_date->age }} anos)
                        @else
                        Não informado
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Gênero:</div>
                    <div>
                        @if($client->gender == 'male')
                        Masculino
                        @elseif($client->gender == 'female')
                        Feminino
                        @elseif($client->gender == 'other')
                        Outro
                        @else
                        Não informado
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="label">Cadastrado em:</div>
                    <div>{{ $client->created_at->format('d/m/Y') }}</div>
                </div>

                <div class="info-completa">
                    <div class="label">Endereço:</div>
                    <div>{{ $client->address ?? 'Não informado' }}</div>
                </div>
            </div>

            <div class="observacoes">
                <div class="label">Observações importantes:</div>
                <p>{{ $client->notes ?? 'Nenhuma observação registrada.' }}</p>
            </div>

            <div class="historico mt-4">
                <div class="historico-titulo">HISTÓRICO DE ATENDIMENTOS</div>

                @if(count($appointments) > 0)
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Descrição</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->date->format('d/m/Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                @if($appointment->end_time)
                                - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $appointment->title }}</strong><br>
                                {{ Str::limit($appointment->description, 80) }}
                            </td>
                            <td>
                                @if($appointment->status == 'scheduled')
                                Agendado
                                @elseif($appointment->status == 'completed')
                                Concluído
                                @elseif($appointment->status == 'cancelled')
                                Cancelado
                                @else
                                {{ ucfirst($appointment->status) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center">Nenhum histórico de atendimento registrado para este cliente.</p>
                @endif
            </div>

            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="assinatura">
                        <div class="linha-assinatura"></div>
                        <p>Assinatura do Cliente</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="assinatura">
                        <div class="linha-assinatura"></div>
                        <p>Assinatura do Atendente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>