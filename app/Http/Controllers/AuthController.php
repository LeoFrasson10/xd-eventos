<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function auth(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
      'password' => 'required',
    ],[
      'email.required' => 'Email é obrigatório',
      'email.email' => 'Email inválido',
      'password.required' => 'Senha é obrigatória',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

      return redirect()->route('site.auth.clients.index');
    } else {
      return redirect()->back()->with('danger', 'Usuário ou senha inválidos');
    }

  }

  public function logout()
  {
    Auth::logout();
    return redirect()->route('site.login');
  }
}
