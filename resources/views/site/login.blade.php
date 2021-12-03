@extends('layouts.site')

@section('title', 'Login')

@section('content')
<main>

  <div class="container-fluid">

    <div class="row content-flex align-items-center justify-content-center">
      <div class="col-4 container-home">
        <div class="row">
          <div class="col-12">
            <h2>XD Eventos</h2>
          </div>
          <div class="col-12">
            <h6>Sistema de controle de clientes</h6>
            <br />
            <p>
              Faça o login ou cadastre-se <br />
              para ter acesso ao sistema.
            </p>
          </div>
        </div>
      </div>
      <div class="col-8 container-form">
        <div class="content-form">
          <form action="#" method="POST">
            @csrf
            <div class="row">
              <div class="col-12 form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
              </div>
              <div class="col-12 form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
              </div>
            </div>
            <div class="row container-buttons">
              <div class="col-3">
                <a href="#" class="btn btn-secondary">Cadastrar</a>
              </div>
              <div class="col-3">
                <button type="submit" class="btn btn-primary">Entrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</main>
@stop
