@extends('frontend.layouts.app')

@section('contents')
  <!-- Breadcrumb component -->
  <x-frontend.breadcrumb :items="[
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Reset Password'],
  ]" />

  <div class="page-content pt-150 pb-135">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
          <div class="row">
            <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
              <div class="login_wrap widget-taber-content background-white">
                <div class="padding_eight_all bg-white">
                  <div class="heading_s1">
                    <h4 class="mb-5">Reset Password</h4>
                  </div>

                  <!-- Formulaire -->
                  <form action="{{ route('password.store') }}" method="POST">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">
                      <input type="email" required=""
                             name="email" placeholder="Your Email *"
                             value="{{ old('email', $request->email) }}"
                      />
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <input required="" type="password"
                             name="password" placeholder="Password"
                      />
                      <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <input required="" type="password"
                             name="password_confirmation" placeholder="Confirm Password"
                      />
                      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-heading btn-block hover-up"
                              name="login">
                        {{ __('Reset Password') }}
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
