{{--<x-guest-layout>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Address -->
    <div>
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />

      <x-text-input id="password" class="block mt-1 w-full"
                      type="password"
                      name="password"
                      required autocomplete="current-password" />

      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
      <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
      </label>
    </div>

    <div class="flex items-center justify-end mt-4">
      @if (Route::has('password.request'))
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
          {{ __('Forgot your password?') }}
        </a>
      @endif

      <x-primary-button class="ms-3">
        {{ __('Log in') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>--}}

@extends('frontend.layouts.app')

@section('contents')
  <!-- Breadcrumb component -->
  <x-frontend.breadcrumb :items="[
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Login'],
  ]" />

  <div class="page-content pt-150 pb-135">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
          <div class="row">
            <div class="col-lg-6 pr-30 d-none d-lg-block">
              <img
                class="border-radius-15"
                src="{{ asset('assets/frontend/dist/imgs/page/login-1.png') }}"
                alt="" />
            </div>
            <div class="col-lg-6 col-md-8">
              <!-- Session Status -->
              <x-auth-session-status class="mb-4" :status="session('status')" />

              <div class="login_wrap widget-taber-content background-white">
                <div class="padding_eight_all bg-white">
                  <div class="heading_s1">
                    <h1 class="mb-5">Login</h1>
                    <p class="mb-30">
                      Don't have an account?
                      <a href="{{ route('register') }}">Create here</a>
                    </p>
                  </div>

                  <!-- Formulaire -->
                  <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                      <input type="email" required=""
                             name="email" placeholder="Your Email *"
                             value="{{ old('email') }}"
                      />
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <input required="" type="password"
                             name="password" placeholder="Your password *"
                      />
                      <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="login_footer form-group mb-50">
                      <!-- Remember Me -->
                      <div class="chek-form">
                        <div class="custome-checkbox">
                          <input class="form-check-input" type="checkbox" name="remember"
                                 id="exampleCheckbox1" value="" />
                          <label class="form-check-label"
                                 for="exampleCheckbox1">
                            <span>Remember me</span>
                          </label>
                        </div>
                      </div>
                      <!-- Forgot Pwd -->
                      <a class="text-muted" href="{{ route('password.request') }}">
                        Forgot password?
                      </a>
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-heading btn-block hover-up"
                              name="login">
                        Log in
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






