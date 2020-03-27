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
              <!-- <div class="col-lg-6 d-none d-lg-block bg-password-image"></div> -->
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Forgot Your Password?</h1>
                    <p class="mb-4">Just enter your email address below and we'll send you a link to reset your password!</p>
                  </div>
                  <form class="user" method="POST" action="{{ route('password.email') }}">
                      @csrf
                    <div class="form-group">
                      <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." autocomplete="off">
                    </div>
                   <button type="submit" class="btn btn-primary btn-user btn-block login-button">
                      Resest
                    </button>
                  </form>
                  <div class="text-center mt-3">
                    <a class="small forgot-password-edit" href="{{ route('login') }}">Already have an account? Login</a>
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