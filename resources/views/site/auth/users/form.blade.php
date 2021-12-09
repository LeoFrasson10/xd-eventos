@extends('layouts.site')

@section('title', $user->id !== null ? 'Editar Usuário' : 'Cadastrar Usuário')

@section('content')
  @extends('layouts.navbar')
  <main>
  <div class="row w-100">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if($user->id !== null)
              <h4 class="card-title">Editar Usuário</h4>
            @else
              <h4 class="card-title">Cadastrar Usuário</h4>
            @endif
          </div>
          <div class="card-body">
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            @if(session('danger'))
              <div class="alert alert-danger">
                {{ session('danger') }}
              </div>
            @endif
            @if($user->id !== null)
              <form role="form" action="{{ route('site.auth.users.update', $user->id) }}" method="POST">
              {{ method_field('PUT') }}
            @else
              <form role="form" action="{{ route('site.auth.users.store') }}" method="POST">
            @endif
              @csrf
              <div class="row w-100">
                <div class="col-6 form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Nome" value="{{ old('name',  $user->name) }}">
                </div>
                <div class="col-6 form-group">
                  <label for="email">E-mail</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" value="{{ old('email', $user->email) }}">
                </div>
              </div>
              <div class="row w-100">
                <div class="col-6 form-group">
                  <label for="genre">Sexo</label>
                  <select name="genre" class="form-select">
                    <option selected>Selecione uma opção</option>
                    <option value="feminino" @if (old("genre", $user->genre) == "feminino") selected @endif>Feminino</option>
                    <option value="masculino" @if (old('genre', $user->genre) == 'masculino') selected @endif>Masculino</option>
                    <option value="nao-informar" @if (old('gender', $user->genre) == 'nao-informar') selected @endif>Não informar</option>
                  </select>
                </div>
                <div class="col-6 form-group">
                  <label for="phone">Telefone</label>
                  <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone', $user->phone)}}" placeholder="Digite seu telefone" data-inputmask="'alias': 'phonebe'">
                </div>
              </div>
              <div class="row w-100">
                <div class="col-4 form-group">
                  <label for="state">UF</label>
                  <select name="state" id="state" value="{{old('state', $user->state)}}" class="form-select" onChange="handleChangeState(this)" >
                    <option selected >Selecione uma opção</option>
                    @foreach($ufs as $state)
                      @if($state->sigla == $user->state)
                        <option value="{{$state->sigla}}" selected>({{$state->sigla}}) {{$state->nome}}</option>
                      @else
                        <option value="{{$state->sigla}}">({{$state->sigla}}) {{$state->nome}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="col-5 form-group">
                  <label for="city">Cidade</label>
                  <select name="city" id="city" value="{{old('city')}}" class="form-select" >
                  </select>
                </div>
                <div class="col-3 form-group">
                  <label for="status">Status</label>
                  <select name="status" id="status" value="{{old('status')}}" class="form-select">
                    <option value="ativo" selected>Ativo</option>
                    <option value="inativo">Inativo</option>
                  </select>
                </div>
                @if($user->id == null)
                  <div class="col-6 form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
                  </div>
                  <div class="col-6 form-group">
                    <label for="password_confirmation">Confirmar Senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua senha">
                  </div>
                @endif
              </div>


              <br />
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
              <button type="submit" class="btn btn-primary">{{$user->id !== null ? "Salvar" : "Cadastrar"}}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>


@stop
@section('js')
  <script>
    var uf = document.getElementById('state');
    var city = document.getElementById('city');
    var userCity = "{{$user->city}}";
    if(uf.value){
      var url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados/"+uf.value+"/municipios";
      fetch(url)
      .then(response => response.json())
      .then(cities => {
        var arrayData = cities.map(function(item){
          return {
            id: item.id,
            nome: item.nome
          };
        });

        var option = '<option value="">Selecionar cidade</option>';

        for(var i = 0; i < arrayData.length; i++) {
          if (arrayData[i].nome === userCity) {
            option += '<option value="'+arrayData[i].nome+'" selected>'+arrayData[i].nome+'</option>';
          } else {
            option += '<option value="'+arrayData[i].nome+'">'+arrayData[i].nome+'</option>';
          }
        }
        city.innerHTML = option;
      })
    }
  </script>
@stop
