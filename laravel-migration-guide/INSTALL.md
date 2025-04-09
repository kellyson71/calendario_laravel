# Instalação e Configuração

## Pré-requisitos
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js e NPM
- Git

## Passos de Instalação

1. **Criar novo projeto Laravel**
```bash
composer create-project laravel/laravel agendesaude
cd agendesaude
```

2. **Instalar dependências**
```bash
composer require barryvdh/laravel-dompdf
npm install tailwindcss @fortawesome/fontawesome-free
```

3. **Configurar banco de dados**
- Criar um banco de dados MySQL
- Copiar o arquivo `.env.example` para `.env`
- Configurar as credenciais do banco de dados no arquivo `.env`

4. **Executar migrations**
```bash
php artisan migrate
```

5. **Configurar autenticação**
```bash
php artisan make:auth
```

6. **Compilar assets**
```bash
npm run dev
```

7. **Configurar servidor web**
- Apache/Nginx apontando para a pasta `public`
- Configurar rewrite rules
- Definir permissões corretas nas pastas

## Estrutura de Pastas

```
agendesaude/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── config/
├── database/
│   └── migrations/
├── public/
│   └── assets/
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── routes/
└── storage/
```

## Configurações Adicionais

1. **Configurar Tailwind CSS**
```bash
npx tailwindcss init
```

2. **Configurar Font Awesome**
Adicionar no arquivo `resources/css/app.css`:
```css
@import '~@fortawesome/fontawesome-free/css/all.css';
```

3. **Configurar DomPDF**
Adicionar no arquivo `config/app.php`:
```php
'providers' => [
    // ...
    Barryvdh\DomPDF\ServiceProvider::class,
],

'aliases' => [
    // ...
    'PDF' => Barryvdh\DomPDF\Facade::class,
],
```

## Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Gerar nova chave de aplicação
php artisan key:generate

# Criar novo controller
php artisan make:controller NomeController

# Criar nova migration
php artisan make:migration create_nome_table

# Criar novo model
php artisan make:model Nome
```

## Troubleshooting

1. **Problemas de permissão**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

2. **Erros de autenticação**
- Verificar configurações do banco de dados
- Limpar cache de sessão
- Verificar configurações de cookies

3. **Problemas com assets**
- Executar `npm run dev` ou `npm run build`
- Verificar permissões da pasta `public`
- Limpar cache do navegador

## Segurança

1. **Configurações recomendadas**
- Usar HTTPS em produção
- Configurar CORS adequadamente
- Implementar rate limiting
- Usar CSRF tokens
- Validar inputs

2. **Backup**
- Configurar backup automático do banco de dados
- Manter cópias dos arquivos importantes
- Documentar procedimentos de recuperação

## Manutenção

1. **Atualizações**
```bash
composer update
npm update
php artisan migrate
```

2. **Monitoramento**
- Configurar logs
- Monitorar uso de recursos
- Implementar alertas

3. **Otimização**
- Configurar cache
- Otimizar queries
- Minificar assets 