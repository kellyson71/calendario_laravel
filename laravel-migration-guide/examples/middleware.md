# Exemplos de Middleware e Serviços

## Middleware

### Authenticate.php
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
```

### CheckRole.php
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
```

### LogRequest.php
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info('Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
            'status' => $response->status(),
        ]);

        return $response;
    }
}
```

## Serviços

### PDFService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    public function generateAppointmentPDF(Appointment $appointment)
    {
        $pdf = PDF::loadView('appointments.pdf', compact('appointment'));
        
        $filename = "appointments/{$appointment->id}.pdf";
        Storage::put($filename, $pdf->output());
        
        return $filename;
    }

    public function generateReportPDF($appointments, $startDate, $endDate)
    {
        $pdf = PDF::loadView('appointments.report', compact('appointments', 'startDate', 'endDate'));
        
        $filename = "reports/report_" . now()->format('Y-m-d_H-i-s') . ".pdf";
        Storage::put($filename, $pdf->output());
        
        return $filename;
    }
}
```

### CalendarService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    public function getMonthAppointments(int $month, int $year): Collection
    {
        return Appointment::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->orderBy('time')
            ->get();
    }

    public function getDayAppointments(Carbon $date): Collection
    {
        return Appointment::whereDate('date', $date)
            ->orderBy('time')
            ->get();
    }

    public function isTimeSlotAvailable(Carbon $date, string $time): bool
    {
        return !Appointment::whereDate('date', $date)
            ->where('time', $time)
            ->exists();
    }

    public function getAvailableTimeSlots(Carbon $date): array
    {
        $startTime = Carbon::createFromTime(8, 0);
        $endTime = Carbon::createFromTime(17, 0);
        $interval = 30; // minutos
        
        $slots = [];
        $currentTime = $startTime->copy();
        
        while ($currentTime <= $endTime) {
            if ($this->isTimeSlotAvailable($date, $currentTime->format('H:i'))) {
                $slots[] = $currentTime->format('H:i');
            }
            $currentTime->addMinutes($interval);
        }
        
        return $slots;
    }
}
```

### NotificationService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendAppointmentConfirmation(Appointment $appointment)
    {
        Mail::send('emails.appointment-confirmation', ['appointment' => $appointment], function ($message) use ($appointment) {
            $message->to($appointment->email)
                   ->subject('Confirmação de Agendamento');
        });
    }

    public function sendAppointmentReminder(Appointment $appointment)
    {
        Mail::send('emails.appointment-reminder', ['appointment' => $appointment], function ($message) use ($appointment) {
            $message->to($appointment->email)
                   ->subject('Lembrete de Consulta');
        });
    }

    public function sendAppointmentCancellation(Appointment $appointment)
    {
        Mail::send('emails.appointment-cancellation', ['appointment' => $appointment], function ($message) use ($appointment) {
            $message->to($appointment->email)
                   ->subject('Cancelamento de Consulta');
        });
    }
}
```

### CacheService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hora

    public function getMonthAppointments(int $month, int $year)
    {
        $key = "appointments:{$month}:{$year}";
        
        return Cache::remember($key, self::CACHE_TTL, function () use ($month, $year) {
            return Appointment::whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();
        });
    }

    public function clearAppointmentsCache(int $month, int $year)
    {
        $key = "appointments:{$month}:{$year}";
        Cache::forget($key);
    }

    public function getAvailableDoctors()
    {
        return Cache::remember('doctors', self::CACHE_TTL, function () {
            return \App\Models\Doctor::active()->get();
        });
    }
}
``` 