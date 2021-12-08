@extends('layouts.site')

@section('title', 'Listagem de Clientes')

@section('content')
  @extends('layouts.navbar')
  <main>
    <div class="container-actions">
      <div class="row w-100">
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif
          <form>
            <div class="row w-100">
              <div class="col-4 form-group">
                <input class="form-control" name="name" value="{{$name ? $name : ''}}"  type="text" placeholder="Procurar pelo nome do cliente" aria-label="Search">
              </div>
              <div class="col-2 form-group">
                <select name="state" id="state" value="{{$state ? $state : ''}}" class="form-select" onChange="handleChangeState(this)" >
                  <option>Estados</option>
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
            <div class="row w-100 align-items-center">
              <div class="col-1 form-group">
                <label>Origens: </label>
              </div>
              <div class="col-5 form-group container-origin">
                <div class="form-check w-100 ">
                  <input class="form-check-input" type="checkbox" value="1" id="facebook" name="facebook">
                  <label class="form-check-label" for="flexCheckChecked">
                    Facebook
                  </label>
                </div>
                <div class="form-check w-100">
                  <input class="form-check-input" type="checkbox" value="1" id="indication" name="indication">
                  <label class="form-check-label" for="flexCheckChecked">
                    Indicação
                  </label>
                </div>
                <div class="form-check w-100">
                  <input class="form-check-input" type="checkbox" value="1" id="website" name="website">
                  <label class="form-check-label" for="flexCheckChecked">
                    Site
                  </label>
                </div>
                <div class="form-check w-100">
                  <input class="form-check-input" type="checkbox" value="1" id="others" name="others">
                  <label class="form-check-label" for="flexCheckChecked">
                    Outros
                  </label>
                </div>
              </div>
              <div class="col-3 form-group">
                <button type="submit" class="btn btn-dark w-100">Pesquisar</button>
              </div>
              <div class="col-3 form-group">
                <a href="{{route('site.auth.clients.form')}}" class="btn btn-primary w-100">Cadastrar Cliente</a>
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
              <th scope="col">CNPJ</th>
              <th scope="col">Cidade/UF</th>
              <th scope="col">Situação</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            @if(count($clients) === 0)
              <tr>
                <td colspan="8">Nenhum cliente cadastrado</td>
              </tr>
            @else
              @foreach($clients as $client)
                <tr>
                  <th scope="row">{{ $client->id }}</th>
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->phone }}</td>
                  <td>{{ $client->document }}</td>
                  <td>{{ $client->city }}/{{$client->state}}</td>
                  <td>{{ $client->status }}</td>
                  <td>
                    <a href="{{ route('site.auth.clients.edit', ['id' => $client->id]) }}" class="btn btn-primary">Editar</a>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalRemove" onClick="handleDeleteClient(this)">Excluir</button>
                  </td>
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
  <div class="modal fade" id="modalRemove" tabindex="-1" aria-labelledby="modalRemove" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRemoveLabel">Excluir Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Tem certeza que deseja excluir este cliente?</p>
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
@section('js')
<script>
  function handleDeleteClient(e) {
    let link = $(e).parent().parent().parent().find('form').attr('action');
    $('#link-remove').attr('action', '{{route('site.auth.clients.destroy', ['id' => $client->id])}}');
  }
  function loadUfs(){
    var uf = document.getElementById('state');
    var url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados";
    var olfUf = <?php echo json_encode($state); ?>;
    // oldUf = olfUf.split('-')
    // console.log(oldUf)
    fetch(url)
    .then(response => response.json())
    .then(states => {
      states.sort(function(a, b){
        if(a.nome < b.nome) return -1;
        if(a.nome > b.nome) return 1;
        return 0;
      });

      var option = '<option value="">Selecionar estado</option>';
      for(state of states){
        if(state.sigla == olfUf){
          option += '<option value="'+state.id+'-'+state.sigla+'" selected>('+state.sigla+')'+state.nome+'</option>';
        }else{
          option += '<option value="'+state.id+'-'+state.sigla+'">('+state.sigla+') '+state.nome+'</option>';
        }

      }
      uf.innerHTML = option;
    })

  }
  // loadUfs()

  function handleChangeState(el){
    var uf = el.value;

    var city = document.getElementById('city');
    var url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados/"+uf+"/municipios";
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

      for(var i = 0; i < arrayData.length; i++){
        option += '<option value="'+arrayData[i].nome+'">'+arrayData[i].nome+'</option>';
      }
      city.innerHTML = option;
    })
  }

</script>
@stop
