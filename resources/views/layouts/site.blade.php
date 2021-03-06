<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body class="antialiased">
    @yield('content')
  </body>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  <script type='text/javascript' src='https://code.jquery.com/jquery-1.11.0.js'></script>
  <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
  <script>

    function handleDelete(e, route) {
      let link = $(e).parent().parent().parent().find('form').attr('action');
      $('#link-remove').attr('action', route);
    }

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
    $(document).ready(function(){
      $('#phone').inputmask({
        mask: ['(99) 9999-9999', '(99) 99999-9999'],
        showMaskOnHover: false,
        showMaskOnFocus: false,
        // removeMaskOnSubmit: true,
      });
    });
  </script>
  @yield('js')
</html>
