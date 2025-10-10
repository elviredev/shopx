@extends('frontend.layouts.app')

@section('contents')
  @include('frontend.home.sections.hero-section')
  <!--End hero slider-->

  @include('frontend.home.sections.category-section')
  <!--End category slider-->

  @include('frontend.home.sections.banner-section')
  <!--End banners-->

  @include('frontend.home.sections.products-tab-section')
  <!--End Products Tabs-->

  @include('frontend.home.sections.banner-section-two')
  <!--End 4 banners-->

  @include('frontend.home.sections.flash-sale-section')
  <!--End Flash Sale-->

  @include('frontend.home.sections.new-arrival-section')
  <!--End new arrival-->

  <section class="wsus__ctg mt-40">
    <div class="container">
      <a href="#" class="wsus__ctg_area">
        <img src="{{ asset('assets/frontend/dist/imgs/cta_bg.png') }}" alt="cta" class="img-fluid w-100"/>
      </a>
    </div>
  </section>
  <!--End CTA section-->

  @include('frontend.home.sections.special-products-section')
  <!--End special products-->

  @include('frontend.home.sections.four-col-products-section')
  <!--End 4 col products-->
@endsection
