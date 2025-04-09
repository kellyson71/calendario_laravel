# Guia de Migração para Laravel - Sistema de Agendamento

## Visão Geral
Este documento serve como guia para recriar o sistema de agendamento em Laravel, mantendo o mesmo layout e funcionalidades do projeto original.

## Estrutura do Projeto

### 1. Autenticação
- Sistema de login com username/senha
- Sessões PHP convertidas para autenticação Laravel
- Middleware de autenticação para rotas protegidas

### 2. Interface Principal
- Layout com sidebar e calendário
- Design responsivo usando Tailwind CSS
- Integração com Font Awesome para ícones

### 3. Funcionalidades Principais
- Visualização de calendário mensal
- Navegação entre meses
- Listagem de consultas na sidebar
- Sistema de agendamento
- Geração de PDFs

## Estrutura de Arquivos Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CalendarController.php
│   │   └── AppointmentController.php
│   ├── Middleware/
│   │   └── Authenticate.php
├── Models/
│   ├── User.php
│   └── Appointment.php
├── Services/
│   └── PDFService.php
resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── sidebar.blade.php
│   ├── calendar/
│   │   └── index.blade.php
│   └── appointments/
│       └── create.blade.php
├── js/
│   └── components/
│       ├── Calendar.js
│       └── Sidebar.js
├── css/
│   └── app.css
public/
├── assets/
│   ├── img/
│   └── css/
```

## Dependências Necessárias

```json
{
    "require": {
        "laravel/framework": "^10.0",
        "barryvdh/laravel-dompdf": "^2.0",
        "laravel/ui": "^4.0"
    },
    "devDependencies": {
        "tailwindcss": "^3.0",
        "@fortawesome/fontawesome-free": "^6.0"
    }
}
```

## Configurações Importantes

### 1. Banco de Dados
- Migrations necessárias para users e appointments
- Configuração do .env para conexão com banco de dados

### 2. Autenticação
- Configuração do sistema de autenticação Laravel
- Middleware para proteção de rotas

### 3. Assets
- Configuração do Tailwind CSS
- Integração do Font Awesome
- Compilação de assets com Laravel Mix

## Passos para Implementação

1. Criar novo projeto Laravel
2. Configurar autenticação
3. Implementar migrations
4. Criar controllers e models
5. Desenvolver views com Blade
6. Implementar JavaScript para calendário
7. Configurar geração de PDFs
8. Testar e otimizar

## Assets Necessários
- Logo da Prefeitura
- Ícones do Font Awesome
- Estilos CSS personalizados
- Scripts JavaScript para calendário

## Considerações de Segurança
- Validação de inputs
- Proteção contra CSRF
- Sanitização de dados
- Controle de acesso baseado em roles

## Performance
- Cache de consultas frequentes
- Otimização de assets
- Lazy loading de componentes
- Paginação de resultados

## Documentação Adicional
Consulte os arquivos na pasta `documentation/` para:
- Detalhes técnicos específicos
- Exemplos de código
- Guias de implementação 