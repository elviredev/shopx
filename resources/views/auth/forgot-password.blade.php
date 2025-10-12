@extends('frontend.layouts.app')

@section('contents')
  <!-- Breadcrumb component -->
  <x-frontend.breadcrumb :items="[
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Forgot Password'],
  ]" />

  <div class="page-content pt-150 pb-135">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
          <div class="row">
            <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
              <!-- Session Status -->
              <x-auth-session-status class="mb-4" :status="session('status')" />

              <div class="login_wrap widget-taber-content background-white">
                <div class="padding_eight_all bg-white">
                  <div class="heading_s1">
                    <h3 class="mb-5">Forgot Password</h3>
                    <p class="mb-30">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                  </div>

                  <!-- Formulaire -->
                  <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="form-group">
                      <input type="email" required=""
                             name="email" placeholder="Your Email *"
                             value="{{ old('email') }}"
                      />
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-heading btn-block hover-up"
                              name="login">
                        {{ __('Email Password Reset Link') }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


















