<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ URL('public/assets/css/bootstrap.min.css') }}" >
    <link rel="stylesheet" href="{{ URL('public/assets/css/custom.css') }}" >
    <link rel="stylesheet" href="{{ URL('public/css/jquery_popconfirm.css') }}" >
    <link href="{{ URL('public/assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/857b93a4d9.js"></script>
    <script src="{{ URL('public/assets/js/jquery-3.3.1.min.js') }}"></script>

    <title>Campaign</title>
  </head>
  <body>
    
    
   
    @yield('content')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{ URL('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL('public/assets/js/custom.js') }}"></script>
    <script src="{{ URL('public/js/jquery_popconfirm.js') }}"></script>
    <script src="{{ URL('public/assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
    
  </body>
</html>