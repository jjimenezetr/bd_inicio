@include('menu.script_header')
@include('menu.inicio_sesion.logueo')
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Iniciar sesion</h5>
            <form class="form-signin" method="POST" action="{{ route('login') }}">
              @csrf
              <div class="form-label-group">
                <input type="text" id="login" name="login" value="{{ old('name') ?: old('email') }}" class="form-control @error('password') is-invalid @enderror" placeholder="Correo" required    >
                @if ($errors->has('name') || $errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>Error de credenciales</strong>
                    </span>
                @endif  
                <label for="login">Usuario, Correo</label>
              </div>
              <div class="form-label-group">
                <input type="password" id="password"  name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contrasena" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="password">Contraseña</label>
              </div>
              <div class="flash-message">
                  @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                         Usuario o contraseña incorrectas
                        </div>
                    @endforeach
                  @endif
              </div>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Iniciar sesión</button>
              <hr class="my-4">
              <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fab fa-google mr-2"></i> Iniciar con google</button>
              <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Iniciar con Facebook</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
