<!DOCTYPE html>
<html lang="en">

<head>
    <link href="{{('admin-assets/assets/css/authentication/form-2.css')}}" rel="stylesheet" type="text/css" />
    @include('partials.header-assets')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="form">
    <div class="form-container outer" id="app">
        <view-login />
    </div>

    @include('partials.footer-assets')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
