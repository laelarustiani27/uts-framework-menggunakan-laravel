<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Maxton Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/extra-icons.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>