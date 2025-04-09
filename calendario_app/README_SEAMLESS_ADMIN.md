# Seamless Admin no Sistema de Agendamento

Este documento descreve como integrar o pacote Seamless Admin no projeto de Sistema de Agendamento.

## Sobre o Seamless Admin

Seamless Admin é um painel de administração simples e intuitivo para Laravel, semelhante ao painel de administração do Django. Ele oferece uma forma rápida de gerenciar os dados do seu aplicativo sem ter que criar interfaces administrativas personalizadas.

## Instalação

Quando os problemas de SSL/TLS do Composer forem resolvidos, você pode instalar o pacote usando o seguinte comando:

```bash
composer require advaith/seamless-admin
```

Em seguida, publique os assets e arquivos de configuração:

```bash
php artisan vendor:publish --provider="Advaith\SeamlessAdmin\SeamlessAdminServiceProvider"
```

## Limpeza de Cache

Se você não encontrar seu modelo registrado na barra lateral, tente limpar o cache:

```bash
php artisan seamless:clear
```

## Uso

Para usar o Seamless Admin, adicione a trait `SeamlessAdmin` nos modelos que deseja gerenciar:

```php
<?php

namespace App\Models;

use Advaith\SeamlessAdmin\Traits\SeamlessAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory, SeamlessAdmin;

    // Seus campos fillable, etc.
}
```

## Exemplo de Configuração

Veja como configuramos o modelo de Agendamento com SeamlessAdmin:

```php
// Campos que podem ser preenchidos
protected $fillable = [
    'nome',
    'data',
    'hora_inicio',
    'hora_fim',
    'descricao',
    'status',
    'user_id'
];

// Configurações para o SeamlessAdmin
public $adminIcon = 'calendar'; // Ícone para a barra lateral
public $adminGroup = 'Sistema'; // Grupo na barra lateral

// Método para definir quais campos aparecem na listagem
public function adminIndexFields(): array
{
    return [
        'id',
        'nome',
        'data',
        'hora_inicio',
        'hora_fim',
        'status'
    ];
}

// Método mágico para exibir o nome do agendamento
public function __toString(): string
{
    return "{$this->nome} - {$this->data}";
}
```

## Acesso ao Painel de Administração

Uma vez instalado, você pode acessar o painel de administração em `/admin` após fazer login no sistema.

## Documentação Oficial

Para mais informações, consulte a documentação oficial do [Seamless Admin](https://github.com/Advaith3600/seamless-admin).
