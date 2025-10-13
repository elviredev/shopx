@extends('frontend.dashboard.dashboard-app')

@section('dashboard_contents')
  <div id="account-detail" >

    <!--Change Name & Email-->
    <div class="card">
      <div class="card-header p-0">
        <h5>Account Details</h5>
      </div>
      <div class="card-body p-0">
        <p>You can edit your account details here.</p>

        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')

          <div class="row mt-30">
            <div class="form-group col-md-12">
              <label>Name <span class="required">*</span></label>
              <input required="" class="form-control"
                     name="name" type="text"
                     value="{{ auth('web')->user()->name }}"
              />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="form-group col-md-12">
              <label>Email Address <span class="required">*</span></label>
              <input required="" class="form-control"
                     name="email" type="email"
                     value="{{ auth('web')->user()->email }}"
              />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="col-md-12">
              <button type="submit"
                      class="btn btn-fill-out submit font-weight-bold"
                      name="submit" value="Submit">Save Change</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <hr>

    <!--Change Password-->
    <div class="card mt-4">
      <div class="card-header p-0">
        <h5>Change Password</h5>
      </div>
      <div class="card-body p-0">
        <p>You can change your password here.</p>

        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          @method('PUT')

          <div class="row mt-30">
            <div class="form-group col-md-12">
              <label>Current Password  <span class="required">*</span></label>
              <input required="" class="form-control" name="current_password"
                     type="password" />
              <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>
            <div class="form-group col-md-12">
              <label>New Password <span class="required">*</span></label>
              <input required="" class="form-control" name="password"
                     type="password" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="form-group col-md-12">
              <label>Confirm Password <span class="required">*</span></label>
              <input required="" class="form-control" name="password_confirmation"
                     type="password" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="col-md-12">
              <button type="submit"
                      class="btn btn-fill-out submit font-weight-bold"
                      name="submit" value="Submit">Save Change</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection




