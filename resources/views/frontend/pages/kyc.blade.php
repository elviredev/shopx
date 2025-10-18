@extends('frontend.layouts.app')

@section('contents')
  <!-- Breadcrumb component -->
  <x-frontend.breadcrumb :items="[
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'KYC Verification'],
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
                  <div class="heading_s1 mb-4">
                    <h4>KYC Verification</h4>
                  </div>

                  <!-- Formulaire -->
                  <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                      <label for="full_name" class="fw-bold">Full Name <span class="text-danger">*</span></label>
                      <input id="full_name" class="form-control ps-1 placeholder-style" type="text" required="" name="full_name" placeholder="John Doe"/>
                      <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <label for="date_of_birth" class="fw-bold">Date of Birth <span class="text-danger">*</span></label>
                      <input type="text" id="date_of_birth"  class="form-control ps-1 placeholder-style" required="" name="date_of_birth" placeholder="1988-06-01"/>
                      <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <label for="gender" class="fw-bold">Gender <span class="text-danger">*</span></label>
                      <select name="gender" id="gender" class="form-control ps-1 placeholder-style">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                      </select>
                      <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <label for="full_address" class="fw-bold">Full Address <span class="text-danger">*</span></label>
                      <input type="text" id="full_address"  class="form-control ps-1 placeholder-style" required="" name="full_address" placeholder="1355 Market Street, San Francisco, CA" />
                      <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <label for="document_type" class="fw-bold">Document Type <span class="text-danger">*</span></label>
                      <select name="document_type" id="document_type" class="form-control ps-1 placeholder-style">
                        <option value="">Select</option>
                        <option value="id_card">ID Card</option>
                        <option value="passport">Passport</option>
                        <option value="driving_licence">Driving Licence</option>
                      </select>
                      <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <label for="document_scan_copy" class="font-weight-bold">Document Scan Copy <span class="text-danger">*</span></label>
                      <input type="file" id="document_scan_copy" required="" name="document_scan_copy" />
                      <x-input-error :messages="$errors->get('document_scan_copy')" class="mt-2" />
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-heading btn-block hover-up">
                        Submit
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
