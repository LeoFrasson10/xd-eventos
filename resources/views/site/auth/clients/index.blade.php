@extends('layouts.site')

@section('title', 'Listagem de Clientes')

@section('content')
  @extends('layouts.navbar')
  <main>
    <div class="container-table">
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead class="table-dark">
            <tr>
              <th scope="col">Cód.</th>
              <th scope="col">Nome</th>
              <th scope="col">E-mail</th>
              <th scope="col">Telefone</th>
              <th scope="col">CNPJ</th>
              <th scope="col">Cidade/UF</th>
              <th scope="col">Situação</th>
            </tr>
          </thead>
          <tbody>
            @if(count($clients) === 0)
              <tr>
                <td colspan="7">Nenhum cliente cadastrado</td>
              </tr>
            @else
              @foreach($clients as $client)
                <tr>
                  <th scope="row">{{ $client->id }}</th>
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->phone }}</td>
                  <td>{{ $client->document }}</td>
                  <td>{{ $client->city }}/{{ $client->state }}</td>
                  <td>{{ $client->status }}</td>
                </tr>
              @endforeach
            @endif

          </tbody>
        </table>
      </div>
    </div>
    <div class="d-flex justify-content-center">
      {{ $clients->links() }}
    </div>
  </main>
@stop
