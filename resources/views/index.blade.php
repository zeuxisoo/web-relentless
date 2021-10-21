<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

<meta name="theme-color" content="#1266F1" />

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ __('LifeNote') }}" />
<meta name="apple-mobile-web-app-status-bar-style" content="white">

<meta name="mobile-web-app-capable" content="yes">

<meta name="csrf-token" content="{{ csrf_token() }}">

@if (config("app.debug") === false)
    <link rel="preconnect" href="//fonts.googleapis.com" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
@endif

<title>{{ __('LifeNote') }}</title>

<link rel="icon" sizes="24x24" type="image/png" href="{{ asset('assets/icons/icon_24.png') }}" />
<link rel="manifest" href="manifest.json" />

<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/icons/icon_192.png') }}" />
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('assets/icons/icon_512.png') }}" />

@if (config("app.debug") === true)
    <link rel="stylesheet" href="http://localhost:8080/assets/css/app.css">
@else
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
@endif

</head>
<body>
<div id="app"></div>
@if (config("app.debug") === true)
    <script src="http://localhost:8080/assets/js/manifest.js"></script>
    <script src="http://localhost:8080/assets/js/vendor.js"></script>
    <script src="http://localhost:8080/assets/js/app.js"></script>
@else
    <script src="{{ mix('assets/js/manifest.js') }}"></script>
    <script src="{{ mix('assets/js/vendor.js') }}"></script>
    <script src="{{ mix('assets/js/app.js') }}"></script>
@endif
</body>
</html>
