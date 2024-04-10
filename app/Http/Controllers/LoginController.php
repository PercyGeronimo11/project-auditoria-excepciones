<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/connect');
        }
        return redirect('/');
    }

    

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users', 'email')->ignore($request->user()),
                function ($attribute, $value, $fail) {
                    $value = trim($value);
                    $allowedDomains = [
                        'gmail.com',
                        'yahoo.com',
                        'hotmail.com',
                        'outlook.com',
                        'icloud.com',
                        'aol.com',
                        'protonmail.com',
                        'gmx.com',
                        'mail.com',
                        'gmail.es',
                        'yahoo.es',
                        'hotmail.es',
                        'outlook.es',
                        'icloud.es',
                    ];
                    $domain = substr(strrchr($value, "@"), 1); // Obtener el dominio del correo electrónico
                    if (!in_array($domain, $allowedDomains)) {
                        $fail('El dominio del correo electrónico no es válido.');
                    }
                },
            ],
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de caracteres.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de caracteres.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede tener más de 50 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
