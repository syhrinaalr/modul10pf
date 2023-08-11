<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/sass/app.scss')
</head>
<body class="bg-primary" >
    <div class="container-sm mt-5 "><br><br><br>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row justify-content-center ">
                <div class="p-5 bg-light rounded-3 border col-xl-4">

                    <div class="mb-3 text-center">
                        <i class="bi-hexagon-fill text-primary me-2 fs-1"></i>
                        <h4>Employee Data Master</h4>
                      </div>
                      <br>
                      <hr>
                      <div class="justify-content-center">
                        <div class="col-13 mb-3">
                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="col-13 mb-3 d-flex flex-column">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter Your Password">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-13 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg mt-3"><i class="bi bi-box-arrow-in-right"></i>
                                {{ __('Login') }}
                            </button>
                        </div>
                      </div>

                </div>
            </div>
        </form>
    </div>





    <div class="container-sm mt-5 "><br><br><br>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row justify-content-center ">
                <div class="p-5 bg-light rounded-3 border col-xl-4">

                    <div class="mb-3 text-center">
                        <i class="bi-hexagon-fill text-primary me-2 fs-1"></i>
                        <h4>Employee Data Master</h4>
                      </div>
                      <br>
                      <hr>
                      <div class="justify-content-center">
                        <div class="col-13 mb-3">
                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="col-13 mb-3 d-flex flex-column">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter Your Password">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-13 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg mt-3"><i class="bi bi-box-arrow-in-right"></i>
                                {{ __('Login') }}
                            </button>
                        </div>
                      </div>

                </div>
            </div>
        </form>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
