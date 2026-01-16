<header id="header" class="header fixed-top d-flex align-items-center" style="background-color:#f1f2f4; height: 80px;">
  <div class="d-flex align-items-center px-3"> <a href="" class="logo d-flex align-items-center">
      <img src="{{asset('assets/img/mon-state-logo.png')}}" alt="Logo" style="height: 60px; width: auto;">
      <span class="d-none d-lg-block ms-3" style="font-size: 26px; font-weight: 700; color: #012970;">
        {{config('app.name')}}
      </span>
    </a>

    <i class="bi bi-list toggle-sidebar-btn ms-4" style="font-size: 30px; cursor: pointer;"></i>
    
  </div>



  <div class="search-bar">
    {{-- <form class="search-form d-flex align-items-center" method="POST" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form> --}}
  </div><!-- End Search Bar -->

  @include('layouts.nav')<!-- End Icons Navigation -->

</header><!-- End Header -->


{{-- @php $isEmbedded = determineIfAppIsEmbedded() @endphp
   <header id="header" class="header fixed-top d-flex align-items-center" @if($isEmbedded) style="background-color:#f1f2f4" @endif>
       <div class="d-flex align-items-center justify-content-between">
           <a href="{{ route('home') }}" class="logo d-flex align-items-center">
               <img src="{{ asset('assets/img/logo.png') }}" alt="">
               <span class="d-none d-lg-block">Employee Management</span>
           </a>
           <i class="bi bi-list toggle-sidebar-btn"></i>
       </div>
       @include('layouts.nav')
   </header> --}}