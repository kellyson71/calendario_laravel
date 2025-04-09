@extends('admin.layout')

@section('title', 'Gerenciar Agendamentos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gerenciar Agendamentos</h1>
    <a href="{{ route('admin.agendamentos.create') }}" class="btn btn-primary">Novo Agendamento</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendamentos as $agendamento)
                    <tr>
                        <td>{{ $agendamento->id }}</td>
                        <td>{{ $agendamento->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($agendamento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($agendamento->hora_fim)->format('H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $agendamento->status == 'agendado' ? 'primary' : ($agendamento->status == 'confirmado' ? 'success' : 'secondary') }}">
                                {{ ucfirst($agendamento->status) }}
                            </span>
                        </td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('admin.agendamentos.show', $agendamento->id) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('admin.agendamentos.edit', $agendamento->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.agendamentos.destroy', $agendamento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este agendamento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum agendamento encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $agendamentos->links() }}
        </div>
    </div>
</div>
@endsection