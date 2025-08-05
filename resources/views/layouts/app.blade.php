{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/rripthbc.png') }}">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    {{-- Icons: Boxicons (Modern & Simple) --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>

    <div class="main-wrapper">
        
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="main-content">
            
            {{-- Navbar --}}
            @include('layouts.navbar')

            {{-- Page Content --}}
            <main class="page-content">
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('layouts.footer')

        </div>
    </div>

    {{-- Main JS --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>