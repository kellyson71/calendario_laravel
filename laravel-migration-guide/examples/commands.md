# Exemplos de Comandos e Jobs

## Comandos

### SendAppointmentReminders.php
```php
<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Envia lembretes de consultas para o dia seguinte';

    public function handle(NotificationService $notificationService)
    {
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::whereDate('date', $tomorrow)
            ->where('status', 'agendado')
            ->get();

        $this->info("Enviando lembretes para {$appointments->count()} consultas...");

        foreach ($appointments as $appointment) {
            $notificationService->sendAppointmentReminder($appointment);
            $this->line("Lembrete enviado para: {$appointment->patient_name}");
        }

        $this->info('Lembretes enviados com sucesso!');
    }
}
```

### GenerateDailyReport.php
```php
<?php

namespace App\Console\Commands;

use App\Services\PDFService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateDailyReport extends Command
{
    protected $signature = 'reports:generate-daily';
    protected $description = 'Gera relatório diário de agendamentos';

    public function handle(PDFService $pdfService)
    {
        $today = Carbon::today();
        $appointments = Appointment::whereDate('date', $today)->get();

        $filename = $pdfService->generateReportPDF(
            $appointments,
            $today,
            $today
        );

        $this->info("Relatório gerado: {$filename}");
    }
}
```

## Jobs

### SendAppointmentConfirmation.php
```php
<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAppointmentConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle(NotificationService $notificationService)
    {
        $notificationService->sendAppointmentConfirmation($this->appointment);
    }
}
```

### ProcessAppointmentCancellation.php
```php
<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAppointmentCancellation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;
    protected $reason;

    public function __construct(Appointment $appointment, string $reason)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
    }

    public function handle(NotificationService $notificationService)
    {
        // Atualiza status do agendamento
        $this->appointment->update([
            'status' => 'cancelado',
            'cancellation_reason' => $this->reason,
        ]);

        // Envia notificação
        $notificationService->sendAppointmentCancellation($this->appointment);

        // Registra no log
        \Log::info('Agendamento cancelado', [
            'appointment_id' => $this->appointment->id,
            'reason' => $this->reason,
        ]);
    }
}
```

### GenerateAppointmentPDF.php
```php
<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\PDFService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAppointmentPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle(PDFService $pdfService)
    {
        $filename = $pdfService->generateAppointmentPDF($this->appointment);
        
        $this->appointment->update([
            'pdf_path' => $filename
        ]);
    }
}
```

## Agendamento de Tarefas

### Kernel.php
```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SendAppointmentReminders::class,
        Commands\GenerateDailyReport::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Envia lembretes às 18h
        $schedule->command('appointments:send-reminders')
                ->dailyAt('18:00');

        // Gera relatório diário à meia-noite
        $schedule->command('reports:generate-daily')
                ->dailyAt('00:00');

        // Limpa PDFs antigos semanalmente
        $schedule->command('cleanup:old-pdfs')
                ->weekly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
```

## Eventos e Listeners

### AppointmentCreated.php
```php
<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }
}
```

### SendAppointmentConfirmationListener.php
```php
<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\SendAppointmentConfirmation;
use App\Jobs\GenerateAppointmentPDF;

class SendAppointmentConfirmationListener
{
    public function handle(AppointmentCreated $event)
    {
        SendAppointmentConfirmation::dispatch($event->appointment);
        GenerateAppointmentPDF::dispatch($event->appointment);
    }
}
```

### EventServiceProvider.php
```php
<?php

namespace App\Providers;

use App\Events\AppointmentCreated;
use App\Listeners\SendAppointmentConfirmationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AppointmentCreated::class => [
            SendAppointmentConfirmationListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
``` 