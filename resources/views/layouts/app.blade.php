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
            @if(Auth::check() && $show_sidebar)
                @include('layouts.aside')
            @endif
        @endif

        <main id="main" class="main" style="margin-top: 70px;"> <!-- Adjusted for fixed header -->
            @if(Auth::check())
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
            @endif

            @include('layouts.success_message')

            @yield('content')
        </main>

        @include('layouts.footer')
        @include('layouts.scripts')

        @yield('scripts')

        <!-- Auto-collapse sidebar if it was hidden last time -->
        @if(Auth::check() && !$show_sidebar)
            <script>
                $(document).ready(function () {
                    $('.toggle-sidebar-btn').click(); // Simulate click to collapse sidebar
                });
            </script>
        @endif
    </body>
</html>