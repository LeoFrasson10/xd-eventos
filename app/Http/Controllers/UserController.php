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

  public function index(Request $request)
  {
    $name = $request->name ? $request->name : '';
    $state = $request->state ? $request->state : 'null';
    $city = $request->city ? $request->city : 'null';
    $status = $request->status ? $request->status : 'null';
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

    usort($ufs, function($a, $b) {
      return strcmp($a->nome, $b->nome);
    });

    if($name != null && $state != "null" && $city != "null")
    {
      if($status != "null"){
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
    } else if ($name && $state != "null") {
      if($status != "null"){
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
    } else if ($name && $city != "null") {
      if($status != "null"){
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($state !== "null" && $city !== "null") {

      if($status != "null"){
        $users = User::where('state', 'like', '%'.$request->state.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('state', 'like', '%'.$request->state.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($name) {
      if($status != "null"){
        $users = User::where('name', 'like', '%'.$name.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('name', 'like', '%'.$name.'%')
        ->paginate(10);
      }
    } else if ($state != "null") {

      if($status != "null"){
        $users = User::where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
    } else if ($city != "null") {
      if($status != "null"){
        $users = User::where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $users = User::where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($status != "null") {

      $users = User::where('status', '=', $status)->paginate(10);
    } else {
      $users = User::paginate(10);
    }



    return view('site.auth.users.index', compact('users','ufs', 'name', 'state', 'city', 'status'));
  }

  public function store(Request $request)
  {
    $path = $request->path();

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'genre' => 'required|string',
      'phone' => 'required|string',
      'state' => 'required',
      'city' => 'required',
      'password' => 'required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/',
    ],[
      'name.required' => 'O campo nome ?? obrigat??rio',
      'name.max' => 'O campo nome deve ter no m??ximo 255 caracteres',
      'email.required' => 'O campo email ?? obrigat??rio',
      'email.email' => 'O campo email deve ser um email v??lido',
      'email.max' => 'O campo email deve ter no m??ximo 255 caracteres',
      'email.unique' => 'O email informado j?? est?? cadastrado',
      'genre.required' => 'O campo g??nero ?? obrigat??rio',
      'phone.required' => 'O campo telefone ?? obrigat??rio',
      'state.required' => 'O campo estado ?? obrigat??rio',
      'city.required' => 'O campo cidade ?? obrigat??rio',
      'password.required' => 'O campo senha ?? obrigat??rio',
      'password.min' => 'O campo senha deve ter no m??nimo 6 caracteres',
      'password.confirmed' => 'As senhas n??o conferem',
      'password.regex' => 'A senha deve conter pelo menos uma letra mai??scula, uma letra min??scula, um n??mero e um caractere especial',
    ]);

    $data = $request->all();
    if($data['genre'] === 'Selecione uma op????o'){
      return redirect()->back()->with('danger', 'O campo g??nero ?? obrigat??rio');
    }
    if($data['state'] === 'Selecione uma op????o'){
      return redirect()->back()->with('danger', 'O campo estado ?? obrigat??rio');
    }
    if($data['city'] === 'Selecione uma op????o'){
      return redirect()->back()->with('danger', 'O campo cidade ?? obrigat??rio');
    }

    $data['password'] = bcrypt($data['password']);

    $user = User::create($data);
    if($user){
      if($path === "usuarios/cadastrar"){
        return redirect()->route('site.auth.users.index')->with('success', 'Usu??rio cadastrado com sucesso!');
      } else {
        return redirect()->route('site.login')->with('success', 'Cadastro realizado com sucesso!');
      }

    }else{
      return redirect()->back()->with('danger', 'Erro ao cadastrar usu??rio!');
    }


  }

  public function create()
  {
    $user = new User;
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
    return view('site.auth.users.form', compact('ufs', 'user'));
  }

  public function destroy($id)
  {
    $userId = Auth::id();
    if($userId == $id){
      return redirect()->back()->with('danger', 'Voc?? n??o pode excluir sua pr??pria conta!');
    } else {
      $user = User::find($id);
      $user->delete();
      return redirect()->route('site.auth.users.index')->with('success', 'Usu??rio exclu??do com sucesso!');
    }
  }

  public function edit($id)
  {
    $user = User::find($id);
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
    return view('site.auth.users.form', compact('user', 'ufs'));
  }

  public function update(Request $request, $id)
  {
    $user = User::find($id);

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
      'genre' => 'required|string',
      'phone' => 'required|string',
      'state' => 'required',
      'city' => 'required',
    ],[
      'name.required' => 'O campo nome ?? obrigat??rio',
      'name.max' => 'O campo nome deve ter no m??ximo 255 caracteres',
      'email.required' => 'O campo email ?? obrigat??rio',
      'email.email' => 'O campo email deve ser um email v??lido',
      'email.max' => 'O campo email deve ter no m??ximo 255 caracteres',
      'email.unique' => 'O email informado j?? est?? cadastrado',
      'genre.required' => 'O campo g??nero ?? obrigat??rio',
      'phone.required' => 'O campo telefone ?? obrigat??rio',
      'state.required' => 'O campo estado ?? obrigat??rio',
      'city.required' => 'O campo cidade ?? obrigat??rio',
    ]);


    $user = User::find($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->state = $request->state;
    $user->city = $request->city;
    $user->status = $request->status;
    $user->save();

    if($user){
      return redirect()->route('site.auth.users.index')->with('success', 'Usu??rio atualizado com sucesso!');
    }else{
      return redirect()->back()->with('danger', 'Erro ao atualizar usu??rio!');
    }

  }
}
