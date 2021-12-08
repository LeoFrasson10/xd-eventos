<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class UserController extends Controller
{
  public function login()
  {
    if(Auth::check()) {
      return redirect()->route('site.auth.clients.index');
    } else {
      return view('site.login');
    }
  }

  public function register()
  {
    $states = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
    $states = $states->json();
    $ufs = array();
    foreach ($states as $key => $value) {
      array_push($ufs,  (object)[
        'id' => $value['id'],
        'sigla' => $value['sigla'],
        'nome' => $value['nome']
      ]);
    }
    // ordenar array
    usort($ufs, function($a, $b) {
      return strcmp($a->nome, $b->nome);
    });
    if(Auth::check()) {
      return redirect()->route('site.auth.clients.index');
    } else {
      return view('site.register', compact('ufs'));
    }
  }

  public function index()
  {
    return view('site.auth.users.index');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'genre' => 'required|string',
      'phone' => 'required|string',
      'state' => 'required',
      'city' => 'required',
      'password' => 'required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/',
    ],[
      'name.required' => 'O campo nome é obrigatório',
      'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
      'email.required' => 'O campo email é obrigatório',
      'email.email' => 'O campo email deve ser um email válido',
      'email.max' => 'O campo email deve ter no máximo 255 caracteres',
      'email.unique' => 'O email informado já está cadastrado',
      'genre.required' => 'O campo gênero é obrigatório',
      'phone.required' => 'O campo telefone é obrigatório',
      'state.required' => 'O campo estado é obrigatório',
      'city.required' => 'O campo cidade é obrigatório',
      'password.required' => 'O campo senha é obrigatório',
      'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
      'password.confirmed' => 'As senhas não conferem',
      'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial',
    ]);

    $data = $request->all();
    if($data['genre'] === 'Selecione uma opção'){
      return redirect()->back()->with('danger', 'O campo gênero é obrigatório');
    }
    if($data['state'] === 'Selecione uma opção'){
      return redirect()->back()->with('danger', 'O campo estado é obrigatório');
    }
    if($data['city'] === 'Selecione uma opção'){
      return redirect()->back()->with('danger', 'O campo cidade é obrigatório');
    }

    $data['password'] = bcrypt($data['password']);

    $user = User::create($data);
    if($user){
      return redirect()->route('site.login')->with('success', 'Cadastro realizado com sucesso!');
    }else{
      return redirect()->back()->with('danger', 'Erro ao cadastrar usuário!');
    }


  }


}
