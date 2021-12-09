<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Client;

class ClientsController extends Controller
{
  public function index(Request $request)
  {
    $states = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
    $name = $request->name ? $request->name : '';
    $state = $request->state ? $request->state : 'null';
    $city = $request->city ? $request->city : 'null';
    $status = $request->status ? $request->status : 'null';

    if($name != null && $state != "null" && $city != "null")
    {

      // dd($name, $city, $newUf[1], $status);
      if($status != "null"){
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
        // dd($clients);
    } else if ($name && $state != "null") {
      if($status != "null"){
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
    } else if ($name && $city != "null") {
      if($status != "null"){
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($state !== "null" && $city !== "null") {

      if($status != "null"){
        $clients = Client::where('state', 'like', '%'.$request->state.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('state', 'like', '%'.$request->state.'%')
        ->where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($name) {
      if($status != "null"){
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('name', 'like', '%'.$name.'%')
        ->paginate(10);
      }
    } else if ($state != "null") {

      if($status != "null"){
        $clients = Client::where('state', 'like', '%'.$request->state.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('state', 'like', '%'.$request->state.'%')
        ->paginate(10);
      }
    } else if ($city != "null") {
      if($status != "null"){
        $clients = Client::where('city', 'like', '%'.$city.'%')
        ->where('status', '=', $status)
        ->paginate(10);
      } else{
        $clients = Client::where('city', 'like', '%'.$city.'%')
        ->paginate(10);
      }
    } else if ($status != "null") {

      $clients = Client::where('status', '=', $status)->paginate(10);
    } else {
      $clients = Client::paginate(10);
    }

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
    return view('site.auth.clients.index', compact('clients','name','state','city', 'status', 'ufs'));
  }

  public function create()
  {
    $client = new Client;
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
    return view('site.auth.clients.form', compact('ufs', 'client'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:clients',
      'document' => 'required|max:255|unique:clients',
      'phone' => 'required',
      'state' => 'required',
      'city' => 'required',
      'status' => 'required',
    ], [
      'name.required' => 'O campo nome é obrigatório',
      'email.required' => 'O campo email é obrigatório',
      'document.required' => 'O campo CNPJ é obrigatório',
      'email.email' => 'O campo email deve ser um email válido',
      'email.unique' => 'O email informado já está cadastrado',
      'phone.required' => 'O campo telefone é obrigatório',
      'state.required' => 'O campo estado é obrigatório',
      'city.required' => 'O campo cidade é obrigatório',
      'status.required' => 'O campo status é obrigatório',
    ]);

    $client = new Client();
    $client->name = $request->name;
    $client->email = $request->email;
    $client->phone = $request->phone;
    $client->document = $request->document;
    $client->state = $request->state;
    $client->city = $request->city;
    $client->status = $request->status;
    $client->facebook_origin = $request->facebook ? $request->facebook : 0;
    $client->indication_origin = $request->indication ? $request->indication : 0;
    $client->website_origin = $request->website ? $request->website : 0;
    $client->other_origin = $request->others ? $request->others : 0;
    $client->observation = $request->observation;
    $client->save();

    if($client){
      return redirect()->route('site.auth.clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }else{
      return redirect()->back()->with('danger', 'Erro ao cadastrar cliente!');
    }

  }

  public function edit($id)
  {
    $client = Client::find($id);
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
    return view('site.auth.clients.form', compact('client', 'ufs'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|max:255',
      'email' => 'required|email|max:255',
      'document' => 'required|max:255',
      'phone' => 'required',
      'state' => 'required',
      'city' => 'required',
      'status' => 'required',
    ], [
      'name.required' => 'O campo nome é obrigatório',
      'email.required' => 'O campo email é obrigatório',
      'document.required' => 'O campo CNPJ é obrigatório',
      'email.email' => 'O campo email deve ser um email válido',
      'phone.required' => 'O campo telefone é obrigatório',
      'state.required' => 'O campo estado é obrigatório',
      'city.required' => 'O campo cidade é obrigatório',
      'status.required' => 'O campo status é obrigatório',
    ]);

    $client = Client::find($id);
    $client->name = $request->name;
    $client->email = $request->email;
    $client->phone = $request->phone;
    $client->document = $request->document;
    $client->state = $request->state;
    $client->city = $request->city;
    $client->status = $request->status;
    $client->facebook_origin = $request->facebook ? $request->facebook : 0;
    $client->indication_origin = $request->indication ? $request->indication : 0;
    $client->website_origin = $request->website ? $request->website : 0;
    $client->other_origin = $request->others ? $request->others : 0;
    $client->observation = $request->observation;
    $client->save();

    if($client){
      return redirect()->route('site.auth.clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }else{
      return redirect()->back()->with('danger', 'Erro ao atualizar cliente!');
    }
  }

  public function destroy($id)
  {
    $client = Client::find($id);
    $client->delete();
    return redirect()->route('site.auth.clients.index')->with('success', 'Cliente excluído com sucesso!');
  }
}
