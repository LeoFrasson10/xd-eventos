@extends('layouts.site')

@section('title', 'Listagem de Usuários')

@section('content')
  @extends('layouts.navbar')
  <main>
    <br />
    <div class="row w-100">
      <div class="col-md-12">
        <h2>Listagem de Usuários</h1>
      </div>
    </div>
    <div class="container-actions">
      <div class="row w-100">
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif
          @if(session('danger'))
              <div class="alert alert-danger">
                {{ session('danger') }}
              </div>
            @endif
          <form>
            <div class="row w-100">
              <div class="col-4 form-group">
                <input class="form-control"  name="name" value="{{$name ? $name : ''}}"  type="text" placeholder="Procurar pelo nome do usuário" aria-label="Search">
              </div>
              <div class="col-2 form-group">
                <select name="state" id="state" value="{{$state ? $state : ''}}" class="form-select" onChange="handleChangeState(this)" >
                  <option value="null">Estados</option>
                  @foreach($ufs as $state)
                    <option value="{{$state->sigla}}">({{$state->sigla}}) {{$state->nome}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3 form-group">
                <select name="city" id="city" value="{{$city ? $city : ''}}" class="form-select" >
                  <option value="null" selected>Cidades</option>
                </select>
              </div>
              <div class="col-3 form-group">
                <select name="status" id="status" value="{{$status ? $status : ''}}" class="form-select">
                  <option value="null" selected>Todos</option>
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
              </div>
            </div>
            <br />
            <div class="row justify-content-md-center">
              <div class="col-3 form-group">
                <button type="submit" class="btn btn-dark w-100">Pesquisar</button>
              </div>
              <div class="col-3 form-group">
                <a href="{{route('site.auth.users.form')}}" class="btn btn-primary w-100">Cadastrar Usuário</a>
              </div>
            </div>
          </form>
      </div>
    </div>
    <div class="container-table">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">Cód.</th>
              <th scope="col">Nome</th>
              <th scope="col">E-mail</th>
              <th scope="col">Telefone</th>
              <th scope="col">Cidade/UF</th>
              <th scope="col">Situação</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            @if(count($users) === 0)
              <tr>
                <td colspan="8">Nenhum usuário cadastrado</td>
              </tr>
            @else
              @foreach($users as $user)
                <tr>
                  <th scope="row">{{ $user->id }}</th>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->phone }}</td>
                  <td>{{ $user->city }}/{{$user->state}}</td>
                  <td>{{ $user->status }}</td>
                  <td>
                    <a href="{{ route('site.auth.users.edit', ['id' => $user->id]) }}" class="btn btn-primary">Editar</a>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalRemove" onClick="handleDelete(this, '{{route('site.auth.users.destroy', ['id' => $user->id])}}')">Excluir</button>
                  </td>
                </tr>
              @endforeach
            @endif

          </tbody>
        </table>
      </div>
    </div>
    <div class="d-flex justify-content-center">
      {{ $users->links() }}
    </div>
  </main>
  <div class="modal fade" id="modalRemove" tabindex="-1" aria-labelledby="modalRemove" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRemoveLabel">Excluir Usuário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Tem certeza que deseja excluir este usuário?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form action="" method="POST" id="link-remove">
              @csrf
              @method('delete')
              <button type="submit" class="btn btn-danger">Confirmar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop
