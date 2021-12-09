@extends('layouts.site')

@section('title', 'Cadastrar-se')

@section('content')
<main>

  <div class="container-fluid">

    <div class="row content-flex flex-register">
      <div class="col-8 container-form form-register">
        <div class="content-form">
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
          <form action="{{ route('auth.register') }}" method="POST" id="form">
            @csrf
            <div class="row">
              <div class="col-12 form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Digite seu nome completo">
              </div>
              <div class="col-12 form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="Digite seu e-mail">
              </div>
              <div class="col-12 form-group">
                <label for="genre">Sexo</label>
                <select name="genre" class="form-select">
                  <option selected>Selecione uma opção</option>
                  <option value="feminino" @if (old("genre") == "feminino") selected @endif>Feminino</option>
                  <option value="masculino" @if (old('genre') == 'masculino') selected @endif>Masculino</option>
                  <option value="nao-informar" @if (old('gender') == 'nao-informar') selected @endif>Não informar</option>
                </select>
              </div>
              <div class="col-12 form-group">
                <label for="phone">Telefone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone')}}" placeholder="Digite seu telefone" data-inputmask="'alias': 'phonebe'">
              </div>
              <div class="col-12 form-group">
                <label for="state">UF</label>
                <select name="state" id="state" value="{{old('state')}}" class="form-select" onChange="handleChangeState(this)" >
                  <option selected >Selecione uma opção</option>
                  @foreach($ufs as $state)
                    <option value="{{$state->sigla}}">({{$state->sigla}}) {{$state->nome}}</option>
                  @endforeach
                </select>

              </div>
              <div class="col-12 form-group">
                <label for="city">Cidade</label>
                <select name="city" id="city" value="{{old('city')}}" class="form-select" >
                </select>
              </div>

              <div class="col-12 form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
              </div>
              <div class="col-12 form-group">
                <label for="password_confirmation">Confirmar Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua senha">
              </div>
            </div>
            <div class="row container-buttons">
              <div class="col-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">Voltar</a>
              </div>
              <div class="col-3">
                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-4 container-home container-register">
        <div class="row">
          <div class="col-12">
            <h2>XD Eventos</h2>
          </div>
          <div class="col-12">
            <h6>Sistema de controle de clientes</h6>
            <br />
            <p>
              Crie uma conta. <br />
              É simples e fácil.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>

</main>
@stop
