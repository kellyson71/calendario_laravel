# Exemplos de Notificações

## AppointmentConfirmation.php
```php
<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Appointment $appointment
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmação de Agendamento')
            ->greeting("Olá {$notifiable->name}!")
            ->line('Seu agendamento foi confirmado com sucesso.')
            ->line("Data: {$this->appointment->start_time->format('d/m/Y H:i')}")
            ->line("Médico: {$this->appointment->doctor->name}")
            ->line("Paciente: {$this->appointment->patient->name}")
            ->action('Ver Agendamento', url("/appointments/{$this->appointment->id}"))
            ->line('Obrigado por usar nosso sistema!');
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'confirmation',
            'message' => 'Seu agendamento foi confirmado',
            'date' => $this->appointment->start_time->format('d/m/Y H:i')
        ];
    }
}
```

## AppointmentReminder.php
```php
<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Appointment $appointment
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lembrete de Agendamento')
            ->greeting("Olá {$notifiable->name}!")
            ->line('Este é um lembrete do seu agendamento amanhã.')
            ->line("Data: {$this->appointment->start_time->format('d/m/Y H:i')}")
            ->line("Médico: {$this->appointment->doctor->name}")
            ->line("Paciente: {$this->appointment->patient->name}")
            ->action('Ver Agendamento', url("/appointments/{$this->appointment->id}"))
            ->line('Não se esqueça de comparecer!');
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'reminder',
            'message' => 'Lembrete de agendamento amanhã',
            'date' => $this->appointment->start_time->format('d/m/Y H:i')
        ];
    }
}
```

## AppointmentCancellation.php
```php
<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancellation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Appointment $appointment,
        private string $reason
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cancelamento de Agendamento')
            ->greeting("Olá {$notifiable->name}!")
            ->line('Seu agendamento foi cancelado.')
            ->line("Data: {$this->appointment->start_time->format('d/m/Y H:i')}")
            ->line("Médico: {$this->appointment->doctor->name}")
            ->line("Paciente: {$this->appointment->patient->name}")
            ->line("Motivo: {$this->reason}")
            ->action('Ver Agendamento', url("/appointments/{$this->appointment->id}"))
            ->line('Entre em contato conosco para reagendar.');
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'cancellation',
            'message' => 'Seu agendamento foi cancelado',
            'reason' => $this->reason,
            'date' => $this->appointment->start_time->format('d/m/Y H:i')
        ];
    }
} 