<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function createDefaultUser()
    {
        // Verificar se o usuário já existe
        if (!User::where('username', 'kellyson')->exists()) {
            User::create([
                'name' => 'Kellyson',
                'username' => 'kellyson',
                'email' => 'kellyson@example.com',
                'password' => Hash::make('kellyson'),
            ]);
            return 'Usuário padrão criado com sucesso!';
        }

        return 'Usuário já existe!';
    }
}
