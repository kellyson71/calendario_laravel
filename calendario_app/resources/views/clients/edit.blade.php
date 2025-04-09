@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Editar Ficha de Cliente</h2>
        <div class="flex space-x-2">
            <a href="{{ route('clients.show', $client->id) }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
                <i class="fas fa-eye mr-2"></i> Ver Detalhes
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Ocorreram erros:</p>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('address', $client->address) }}</textarea>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', $client->birth_date ? $client->birth_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gênero</label>
                    <select name="gender" id="gender"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione...</option>
                        <option value="male" {{ old('gender', $client->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ old('gender', $client->gender) == 'female' ? 'selected' : '' }}>Feminino</option>
                        <option value="other" {{ old('gender', $client->gender) == 'other' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="notes" id="notes" rows="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
                <i class="fas fa-save mr-2"></i> Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection