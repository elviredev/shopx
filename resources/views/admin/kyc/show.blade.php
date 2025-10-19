@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">All KYC Requests</h3>
        <div class="card-actions">
          <a href="{{ url()->previous() }}" class="btn btn-secondary btn-3">
            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
            Back
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <tbody>

              <tr>
                <td>Full Name</td>
                <td>{{ $kyc_request->full_name }}</td>
              </tr>
              <tr>
                <td>Birth Date</td>
                <td>{{ $kyc_request->date_of_birth }}</td>
              </tr>
              <tr>
                <td>Gender</td>
                <td>{{ $kyc_request->gender }}</td>
              </tr>
              <tr>
                <td>Full Address</td>
                <td>{{ $kyc_request->full_address }}</td>
              </tr>
              <tr>
                <td>Document Type</td>
                <td>{{ $kyc_request->document_type }}</td>
              </tr>

              <tr>
                <td>Document Scan Copy</td>
                <td>
                  <a href="{{ route('admin.kyc.download', $kyc_request) }}" class="btn btn-primary">Download</a>
                </td>
              </tr>

              <tr>
                <td>Status</td>
                @if($kyc_request->status == 'pending')
                  <td class="text-secondary">
                    <span class="badge bg-warning-lt">Pending</span>
                  </td>
                @elseif($kyc_request->status == 'approved')
                  <td class="text-secondary">
                    <span class="badge bg-success-lt">Approved</span>
                  </td>
                @else
                  <td class="text-secondary">
                    <span class="badge bg-danger-lt">Rejected</span>
                  </td>
                @endif
              </tr>

              <tr>
                <td>Change Status</td>
                <td>
                  <form action="{{ route('admin.kyc.update', $kyc_request) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="input-group">
                      <select name="status" id="" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                      </select>
                      <button type="submit" class="btn btn-outline-blue">Update</button>
                    </div>
                  </form>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
