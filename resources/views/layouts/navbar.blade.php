<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ route('site.auth.clients.index') }}">XD Eventos</a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('clientes') ? 'active' : '' }}" aria-current="page" href="{{ route('site.auth.clients.index') }}">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('usuarios') ? 'active' : '' }}" href="{{ route('site.auth.users.index') }}">Usuários</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
