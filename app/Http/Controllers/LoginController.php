<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'password' => 'required|min:8',
        ], [
            'userName.required' => 'El nombre de usuario es obligatorio.',
            'userName.email' => 'El nombre de usuario debe tener un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);
        $credentials = $request->only('userName', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/connect');
        }
        if (!User::where('userName', $request->userName)->exists()) {
            return redirect('/')->with(
                'userName' , 'El nombre de usuario ingresado es incorrecto.'
            );
        }else{
            return redirect('/')->with(
                'contraseña', 'La contraseña ingresada es incorrecta.'
            );
        }
        
        //return redirect('/')->with('success', 'Error de inicio de sesion');
    }

    public function register(Request $request)
    {
        $email_full="";
        
        $request->validate([
            'name' => 'required|string|max:50',
            'userName' => 'required|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de caracteres.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'userName.required' => 'El nombre de usuario es obligatorio.',
            'userName.string' => 'El nombre de usuario debe ser una cadena de caracteres.',
            'userName.max' => 'El nombre de usuario no puede tener más de 50 caracteres.',
            'userName.unique' => 'El nombre de usuario ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);
        User::create([
            'name' => $request->name,
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/users')->with('success1', '¡Registro exitoso!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function list(){
        $users = User::where('is_admin',0)->get();
        return view('usuarios.listUsers', compact('users'));
    }

    public function list2(){
        $users = User::where('is_admin',0)->get();
        return view('usuarios.listUsers_inhabil', compact('users'));
    }
    
    public function create(){
        return view('usuarios.register');
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/users');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.editUser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Valida los datos del formulario
        $request->validate([
            'name' => 'required|string|max:50',
            'userName' => 'required|string|max:50|unique:users,userName,'.$id, // Asegúrate de que el nombre de usuario sea único, excluyendo el usuario actual
            'password' => 'nullable|string|min:8', // La contraseña es opcional
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de caracteres.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'userName.required' => 'El nombre de usuario es obligatorio.',
            'userName.string' => 'El nombre de usuario debe ser una cadena de caracteres.',
            'userName.max' => 'El nombre de usuario no puede tener más de 50 caracteres.',
            'userName.unique' => 'El nombre de usuario ya está en uso.',
            'password.string' => 'La contraseña debe ser una cadena de caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        // Encuentra al usuario que se va a actualizar
        $user = User::find($id);

        // Verifica si el usuario existe
        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Actualiza los datos del usuario
        $user->name = $request->name;
        $user->userName = $request->userName;
        if ($request->password) {
            $user->password = bcrypt($request->password); // Solo actualiza la contraseña si se proporciona
        }
        $user->save();

        return redirect('/users')->with('success', 'Usuario actualizado correctamente');
    }
}
