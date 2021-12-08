@extends('layouts.site')

@section('title', $client->id !== null ? 'Editar Cliente' : 'Cadastrar Cliente')

@section('content')
  @extends('layouts.navbar')
  <main>
    <div class="row w-100">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if($client->id !== null)
              <h4 class="card-title">Editar Cliente</h4>
            @else
              <h4 class="card-title">Cadastrar Cliente</h4>
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
            @if($client->id !== null)
              <form role="form" action="{{ route('site.auth.clients.update', $client->id) }}" method="POST">
              {{ method_field('PUT') }}
            @else
              <form role="form" action="{{ route('site.auth.clients.store') }}" method="POST">
            @endif
              @csrf
              <div class="row w-100">
                <div class="col-6 form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Nome" value="{{ old('name',  $client->name) }}">
                </div>
                <div class="col-6 form-group">
                  <label for="email">E-mail</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" value="{{ old('email', $client->email) }}">
                </div>
              </div>
              <div class="row w-100">
                <div class="col-6 form-group">
                  <label for="document">CNPJ</label>
                  <input type="document" class="form-control" name="document" id="document" placeholder="CNPJ" value="{{ old('document', $client->document) }}">
                </div>
                <div class="col-6 form-group">
                  <label for="phone">Telefone</label>
                  <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone', $client->phone)}}" placeholder="Digite seu telefone" data-inputmask="'alias': 'phonebe'">
                </div>
              </div>
              <div class="row w-100">
                <div class="col-4 form-group">
                  <label for="state">UF</label>
                  <select name="state" id="state" value="{{old('state', $client->state)}}" class="form-select" onChange="handleChangeState(this)" >
                    <option selected >Selecione uma opção</option>
                    @foreach($ufs as $state)
                      @if($state->sigla == $client->state)
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
              </div>
              <br />
              <div class="row w-100 align-items-center">
                <div class="col-1 form-group">
                  <label>Origem: </label>
                </div>
                <div class="col-5 form-group container-origin">
                  <div class="form-check w-100 ">
                    <input class="form-check-input" type="checkbox" value="1" id="facebook" name="facebook" {{$client->facebook_origin == 1 ? 'checked' : ''}}>
                    <label class="form-check-label" for="flexCheckChecked">
                      Facebook
                    </label>
                  </div>
                  <div class="form-check w-100">
                    <input class="form-check-input" type="checkbox" value="1" id="indication" name="indication" {{$client->indication_origin == 1 ? 'checked' : ''}}>
                    <label class="form-check-label" for="flexCheckChecked">
                      Indicação
                    </label>
                  </div>
                  <div class="form-check w-100">
                    <input class="form-check-input" type="checkbox" value="1" id="website" name="website" {{$client->website_origin == 1 ? 'checked' : ''}}>
                    <label class="form-check-label" for="flexCheckChecked">
                      Site
                    </label>
                  </div>
                  <div class="form-check w-100">
                    <input class="form-check-input" type="checkbox" value="1" id="others" name="others" {{$client->other_origin == 1 ? 'checked' : ''}}>
                    <label class="form-check-label" for="flexCheckChecked">
                      Outros
                    </label>
                  </div>
                </div>
              </div>
              <br />
              <div class="row w-100">
                <div class="col-12 form-group">
                  <label for="observation">Observações</label>
                  <textarea class="form-control" name="observation" id="observation" cols="30" rows="5">{{ old('observation', $client->observation)}}</textarea>
                </div>
              </div>

              <br />
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
              <button type="submit" class="btn btn-primary">{{$client->id !== null ? "Salvar" : "Cadastrar"}}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>


@stop

@section('js')
<script>
  $(document).ready(function(){
    $('#phone').inputmask({
      mask: ['(99) 9999-9999', '(99) 99999-9999'],
      showMaskOnHover: false,
      showMaskOnFocus: false,
      // removeMaskOnSubmit: true,
    });
    $('#document').inputmask({
      mask: ['99.999.999/9999-99'],
      showMaskOnHover: false,
      showMaskOnFocus: false,
      // removeMaskOnSubmit: true,
    });
  });

    var uf = document.getElementById('state');
    var city = document.getElementById('city');

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
          if (arrayData[i].nome === "{{$client->city}}") {
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
