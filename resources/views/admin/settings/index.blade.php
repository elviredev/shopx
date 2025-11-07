@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="row g-0">
        <div class="col-12 col-md-3 border-end">
          <div class="card-body">
            <h4 class="subheader">Settings</h4>
            <div class="list-group list-group-transparent">
              <a href="./settings.html" class="list-group-item list-group-item-action d-flex align-items-center active">General Settings</a>
            </div>
            <h4 class="subheader mt-4">Experience</h4>
            <div class="list-group list-group-transparent">
              <a href="#" class="list-group-item list-group-item-action">Give Feedback</a>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-9 d-flex flex-column">
          @yield('settings_contents')
        </div>
      </div>
    </div>
  </div>
@endsection




