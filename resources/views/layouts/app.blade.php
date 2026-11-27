<!doctype html>
<html lang="id" data-tema="{{ session('tema', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Colorbox') — Colorbox</title>
    <link rel="icon" href="{{ asset('images/logo/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('gaya')
</head>
<body>
<a class="cb-lompat" href="#isi">Lompat ke isi utama</a>

@include('partials.header')

<main id="isi">
    @include('partials.pesan')
    @yield('isi')
</main>

@include('partials.footer')

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('skrip')
</body>
</html>
