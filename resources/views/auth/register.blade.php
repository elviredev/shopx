{{--<x-guest-layout>
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div>
      <x-input-label for="name" :value="__('Name')" />
      <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />

      <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
      <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

      <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />

      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
      <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
        {{ __('Already registered?') }}
      </a>

      <x-primary-button class="ms-4">
        {{ __('Register') }}
      </x-primary-button>
    </div>
  </form>
</x-guest-layout>--}}

@extends('frontend.layouts.app')

@section('contents')
  <!-- Breadcrumb component -->
  <x-frontend.breadcrumb
    :items="[
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Register'],
    ]"/>

  <div class="page-content pt-150 pb-140">
    <div class="container">
      <div class="row">
        <div class="col-12 m-auto">
          <div class="row align-items-center">
            <div class="col-lg-6 offset-lg-3">
              <div class="login_wrap widget-taber-content background-white">
                <div class="padding_eight_all bg-white">
                  <div class="heading_s1">
                    <h2 class="mb-5">Create an Account</h2>
                    <p class="mb-30">Already have an account?
                      <a href="{{ route('login') }}">Login</a>
                    </p>
                  </div>

                  <!-- Formulaire -->
                  <form method="POST" action="{{ route('register') }}" >
                    @csrf

                    <div class="form-group">
                      <input type="text" required="" name="name" placeholder="Name" value="{{ old('name') }}" />
                      <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <input type="email" required="" name="email" placeholder="Email" value="{{ old('email') }}" />
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="form-group">
                      <input required="" type="password" name="password" placeholder="Password" />
                      <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="form-group">
                      <input required="" type="password" name="password_confirmation" placeholder="Confirm password" />
                      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="payment_option mb-30">
                      <div class="custome-radio">
                        <input class="form-check-input" required="" type="radio"
                               name="payment_option" id="exampleRadios3" checked="" />
                        <label class="form-check-label" for="exampleRadios3"
                               data-bs-toggle="collapse" data-target="#bankTranfer"
                               aria-controls="bankTranfer">I am a customer</label>
                      </div>
                      <div class="custome-radio">
                        <input class="form-check-input" required="" type="radio"
                               name="payment_option" id="exampleRadios4" checked="" />
                        <label class="form-check-label" for="exampleRadios4"
                               data-bs-toggle="collapse" data-target="#checkPayment"
                               aria-controls="checkPayment">I am a vendor</label>
                      </div>
                    </div>
                    <div class="form-group mb-0">
                      <button type="submit"
                              class="btn btn-fill-out btn-block hover-up font-weight-bold"
                              name="login">Submit &amp; Register</button>
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














