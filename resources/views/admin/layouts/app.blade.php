<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Dashboard</title>
  <!-- BEGIN PAGE LEVEL STYLES -->
{{--  <link href="./dist/libs/jsvectormap/dist/jsvectormap.css?1750026893" rel="stylesheet" />--}}
  <!-- END PAGE LEVEL STYLES -->

  <!-- JQuery Upload Preview -->
  <link rel="stylesheet" href="{{ asset('assets/global/upload-preview/upload-preview.css') }}">
  <!-- CDN Tabler Icons -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.35.0/dist/tabler-icons.min.css" />
  <!-- CDN Notyf -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  <!-- CDN jQuery Nestable (drag&drop) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
  <!-- CDN Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link href="{{ asset('assets/admin/dist/css/tabler.css') }}" rel="stylesheet" />
  <!-- BEGIN CUSTOM FONT -->
  <style>
    @import url("https://rsms.me/inter/inter.css");
  </style>
  <!-- END CUSTOM FONT -->

  @stack('styles')
</head>
<body>
<!-- BEGIN GLOBAL THEME SCRIPT -->
<script src="{{ asset('assets/admin/dist/js/tabler-theme.min.js') }}"></script>
<!-- END GLOBAL THEME SCRIPT -->

<div class="page">

  <!--  BEGIN SIDEBAR  -->
  @include('admin.layouts.sidebar')
  <!--  END SIDEBAR  -->

  <div class="page-wrapper">
    <!-- BEGIN PAGE BODY -->
    <div class="page-body">
      @yield('contents')
    </div>
    <!-- END PAGE BODY -->

    <!--  BEGIN FOOTER  -->
    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
        <div class="row text-center align-items-center flex-row-reverse">

          <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <ul class="list-inline list-inline-dots mb-0">
              <li class="list-inline-item">
                Copyright &copy; 2025
                <a href="https://www.monsite.com" class="link-secondary">Elviredev</a>. All rights reserved.
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)" class="link-secondary" rel="noopener"> v1.0.0 </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!--  END FOOTER  -->
  </div>
</div>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- TinyMCE Rich Editor -->
<script src="{{ asset('assets/admin/dist/js/tinymce/tinymce.min.js') }}"></script>

<!-- JQuery Upload Preview -->
<script src="{{ asset('assets/global/upload-preview/upload-preview.min.js') }}"></script>
<!-- Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CDN Notyf JS -->
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<!-- CDN jQuery Nestable (drag&drop) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<!-- CDN Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('assets/admin/dist/js/tabler.min.js') }}" defer></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->
@include('admin.layouts.script')
@stack('scripts')
</body>
</html>
