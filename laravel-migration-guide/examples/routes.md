# Exemplos de Rotas e Configurações

## routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentController;

// Rotas de autenticação
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [CalendarController::class, 'index'])->name('dashboard');
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'generatePDF']);
});
```

## routes/api.php
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/appointments', [CalendarController::class, 'getAppointments']);
    Route::get('/consultas', [AppointmentController::class, 'getConsultas']);
});
```

## Configurações do Laravel

### config/auth.php
```php
<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
```

### config/app.php (trecho relevante)
```php
<?php

return [
    // ...
    'providers' => [
        // ...
        Barryvdh\DomPDF\ServiceProvider::class,
    ],
    // ...
    'aliases' => [
        // ...
        'PDF' => Barryvdh\DomPDF\Facade::class,
    ],
];
```

### .env (trecho relevante)
```env
APP_NAME="AgendeSaúde PDF"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agendesaude
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
``` 