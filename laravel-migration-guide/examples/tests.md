# Exemplos de Testes

## Testes de Autenticação

### AuthControllerTest.php
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
```

## Testes de Calendário

### CalendarControllerTest.php
```php
<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                        ->get('/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_can_get_appointments_for_month()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create([
            'date' => now()->startOfMonth(),
        ]);

        $response = $this->actingAs($user)
                        ->getJson('/api/appointments', [
                            'month' => now()->month,
                            'year' => now()->year,
                        ]);

        $response->assertStatus(200)
                ->assertJsonCount(1);
    }
}
```

## Testes de Agendamento

### AppointmentControllerTest.php
```php
<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_appointment()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                        ->postJson('/appointments', [
                            'patient_name' => 'João Silva',
                            'date' => now()->format('Y-m-d'),
                            'time' => '14:00',
                            'doctor' => 'Dr. Maria',
                        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('appointments', [
            'patient_name' => 'João Silva',
        ]);
    }

    public function test_can_generate_pdf()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($user)
                        ->get("/appointments/{$appointment->id}/pdf");

        $response->assertStatus(200)
                ->assertHeader('Content-Type', 'application/pdf');
    }
}
```

## Testes de Model

### AppointmentTest.php
```php
<?php

namespace Tests\Unit;

use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_appointment()
    {
        $appointment = Appointment::factory()->create();
        
        $this->assertModelExists($appointment);
    }

    public function test_appointment_has_required_fields()
    {
        $appointment = Appointment::factory()->create();
        
        $this->assertNotNull($appointment->patient_name);
        $this->assertNotNull($appointment->date);
        $this->assertNotNull($appointment->time);
        $this->assertNotNull($appointment->doctor);
    }

    public function test_can_scope_appointments_for_month()
    {
        $currentMonth = Appointment::factory()->create([
            'date' => now()->startOfMonth(),
        ]);
        
        $nextMonth = Appointment::factory()->create([
            'date' => now()->addMonth()->startOfMonth(),
        ]);

        $appointments = Appointment::forMonth(
            now()->month,
            now()->year
        )->get();

        $this->assertCount(1, $appointments);
        $this->assertEquals($currentMonth->id, $appointments->first()->id);
    }
}
```

## Testes de JavaScript

### calendar.test.js
```javascript
import Calendar from './calendar';

describe('Calendar', () => {
    let calendar;
    let mockElement;

    beforeEach(() => {
        mockElement = document.createElement('div');
        mockElement.id = 'calendar-body';
        document.body.appendChild(mockElement);
        
        calendar = new Calendar();
    });

    afterEach(() => {
        document.body.removeChild(mockElement);
    });

    test('should initialize calendar', () => {
        expect(calendar.currentDate).toBeInstanceOf(Date);
        expect(calendar.calendarBody).toBe(mockElement);
    });

    test('should change month', () => {
        const initialMonth = calendar.currentDate.getMonth();
        calendar.changeMonth(1);
        expect(calendar.currentDate.getMonth()).toBe(initialMonth + 1);
    });

    test('should go to today', () => {
        const today = new Date();
        calendar.goToToday();
        expect(calendar.currentDate.getDate()).toBe(today.getDate());
        expect(calendar.currentDate.getMonth()).toBe(today.getMonth());
        expect(calendar.currentDate.getFullYear()).toBe(today.getFullYear());
    });
});
``` 