<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Vue Blog</title>
    
    <!-- Bootstrap Cdn Icon -->  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="{{asset('backend/assets/css/portal.css')}}">
    
    <script>
        (function () {
            window.Laravel = {
                csrfToken: '{{csrf_token()}}'
            };
        })();

    </script>
    </head>
    <body class="app">

        <div id="app">
            <App></App>
        </div>



<script src="{{mix('js/app.js')}}"></script>
<!-- FontAwesome JS-->
<script defer src="{{asset('backend/assets/plugins/fontawesome/js/all.min.js')}}"></script>
<!-- Javascript -->          
    <script src="{{asset('backend/assets/plugins/popper.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>  
    <!-- Charts JS -->
    <script src="{{asset('backend/assets/plugins/chart.js/chart.min.js')}}"></script> 
    <script src="{{asset('backend/assets/js/index-charts.js')}}"></script> 
    <!-- Page Specific JS -->
    <script src="{{asset('backend/assets/js/app.js')}}"></script> 
    </body>
</html>
