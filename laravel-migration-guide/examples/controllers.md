# Exemplos de Controllers

## AuthController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Credenciais inválidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
```

## CalendarController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function getAppointments(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $appointments = Appointment::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return response()->json($appointments);
    }
}
```

## AppointmentController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'doctor' => 'required',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json($appointment);
    }

    public function generatePDF(Appointment $appointment)
    {
        $pdf = PDF::loadView('appointments.pdf', compact('appointment'));
        return $pdf->download('agendamento.pdf');
    }
}
``` 