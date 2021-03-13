<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Admin Vue Login</title>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="{{asset('backend/assets/css/portal.css')}}">
    </head>
    <body class="app app-login p-0">

        <div id="app">
            <login></login>
        </div>



<script src="{{mix('js/app.js')}}"></script>
<script defer src="{{asset('backend/assets/plugins/fontawesome/js/all.min.js')}}"></script>

    </body>
</html>
