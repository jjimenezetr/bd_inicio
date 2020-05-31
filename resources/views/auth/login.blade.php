@include('menu.script_header')
@include('menu.inicio_sesion.logueo')
<body>
  <div class="container  h-100 mx-auto" >
    <div class="row align-items-center h-100 mx-auto" >
        <div class="card col-lg-5 mx-auto   cardBackground mx-auto" >
        <!-- style="background: url('img/circulo2.png') no-repeat -45% -45% #f1f1f, url('img/circulo2.png') no-repeat 5% 5% " -->
          <div class="col-sm-11 col-md-11 col-lg-11 mx-auto ">
            <div class="card card-change my-5 mx-auto" >
            <!-- card-signin -->
              <div class="card-body ">
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
                  <a class="pull-right text-primary mt-2">Olvido su contraseña?</a>
                  <div class="row col-12 mt-5 text-center">
                    <div class="col-12 ">
                      <button class="btn  btn-google  text-uppercase" type="submit"><i class="fab fa-google"></i> </button>
                      <button class="btn  btn-facebook  text-uppercase" type="submit"><i class="fab fa-facebook-f"></i> </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</body>
