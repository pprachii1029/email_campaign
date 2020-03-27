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
                  <form class="user" action="{{ route('register') }}" method="POST">
					<h4 class="pb-3 text-center" style="">Sign Up</h4>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Your Name" name="name" autocomplete="off">
                    </div>
					<div class="form-group">
                      <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address" name="email" autocomplete="off">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password" autocomplete="off">
                    </div>
					<div class="form-group">
                      <input type="tel" class="form-control form-control-user" id="exampleInputPassword" placeholder="Phone Number" name="phone_number" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block login-button">
                      Sign Up
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