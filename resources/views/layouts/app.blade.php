@php
    $sidebar_key = Auth::user() ? Auth::user()->getSidebarKey() : 'sidebar_guest';
    $show_sidebar = Auth::check() ? Session::get($sidebar_key, true) : false;
@endphp
<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('layouts.header')
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
        @hasrole('Admin')
            @if($show_sidebar)
                @include('layouts.aside')
            @endif
        @endhasrole
    @endif
    <main id="main" class="main mt-5">
        @if(Auth::check())
            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
        @endif
        @include('layouts.success_message')
        @yield('content')
    </main>
    @include('layouts.footer')
    @include('layouts.scripts')
    @yield('scripts')
    @if(!$show_sidebar && Auth::check())
    <script>
        $(document).ready(function () {
            $('.toggle-sidebar-btn').click();
        });
    </script>
    @endif
</body>
</html>