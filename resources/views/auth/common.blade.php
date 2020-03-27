<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="{{ URL('public/assets/css/bootstrap.min.css') }}" >
        <link rel="stylesheet" href="{{ URL('public/assets/css/custom.css') }}" >
        <link href="{{ URL('public/assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/857b93a4d9.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="{{ URL('public/assets/js/jquery-3.3.1.min.js') }}"></script>
        <title>Login</title>
        
        <style>
            .form-control{
                padding: 1.375rem .75rem !important;
            }
        </style>
	
    </head>
    <body class="bg-gradient-primary">

        @yield('content')

        @if(count(@$errors))
            @foreach ($errors->all() as $error)
                <script>
                    $(document).ready(function(){
                        error('{{ $error }}');
                    });
                </script>
            @endforeach
        @endif


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{ URL('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL('public/assets/js/custom.js') }}"></script>
    <script src="{{ URL('public/assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
    
    </body>
</html>