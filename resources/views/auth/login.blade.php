@extends('auth.common')

@section('content')
<section class="login-back-section">
  <div class="container my-container-edit">

    <!-- Outer Row -->
    <div class="row login-form-center">
      <div class="col-xl-4 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
					 <h1 class="pb-5">LOGO</h1>
                  </div>
                  <form class="user" method="POST" action="{{ route('login') }}">
                  @csrf
                    <h4 class="pb-3 text-center" style="">Login</h4>
                    <div class="form-group">
                      <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." autocomplete="off">
                    </div>
                    <div class="form-group">
                      <input name="password" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" autocomplete="off">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input name="remember" type="checkbox" class="custom-control-input" id="customCheck" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customCheck">{{ __('Remember Me') }}</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block login-button">
                      Login
                    </button>
                  </form>
                  <div class="text-right mt-3">
                    <a class="small forgot-password-edit" href="{{ route('password.request') }}">Forgot Password?</a>
                  </div>
				  <div class="text-center mt-3">
                    <a class="small forgot-password-edit" href="{{ route('register') }}">Don't have an account? Sign up</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection