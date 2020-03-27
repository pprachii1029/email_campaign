<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- today --}}
    <link rel="stylesheet" type="text/css" href="{{ URL('public/assets/css/theme.css') }}">

    <link rel="stylesheet" href="{{ URL('public/assets/css/bootstrap.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{ URL('public/assets/css/custom.css') }}" >

	<link rel="stylesheet" href="{{ URL('public/assets/css/custom2.css') }}" >
    <link rel="stylesheet" href="{{ URL('public/css/jquery_popconfirm.css') }}" >
    <link href="{{ URL('public/assets/plugins/alert/css/alertify.min.css') }}" rel="stylesheet" />
    <link href="{{ URL('public/assets/plugins/holdon/HoldOn.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ URL('public/assets/css/style.css') }}">
    <!-- script -->
    <script src="https://kit.fontawesome.com/857b93a4d9.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="{{ URL('public/assets/js/jquery-3.3.1.min.js') }}"></script> -->
    <script src="https://parsleyjs.org/dist/parsley.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-alpha1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-alpha1/html2canvas.svg.min.js"></script>

    

    <title>Campaign</title>
    <style type="text/css">
        .record_btn::after {
            content: "" !important;
        }
        .parsley-errors-list{
            list-style: none;
            padding: 0;
            margin-top: 10px;
            color: red;
        }
        .hide{    display: none;}
    </style>
  </head>
  <body>
    
    <div class="aa auth_loader hide">
        <img src="{{ URL('public/images/ajax-loader.gif') }}">
    </div>
   <div class="container-fluid top-bar d-flex upp2">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="">LOGO</h2>
        <button type="button" id="sidebarCollapse1" class="btn  sidebar-toggle-btn1">
            <i class="fas fa-align-left"></i>

        </button>
        <div class="top-bar-right d-flex align-items-center">
            <img class="logo-img" src="{{ URL('public/assets/img/top-pofile.png') }}">
            <div class="dropdown dropdown-menu-right">
                <i class="fas fa-chevron-down text-white pl-3 " id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">VIEW PROFILE</a>
                    <a class="dropdown-item" href="{{ route('logout') }}">LOGOUT</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!--SIDEBAR-->

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <h3 class="">MENU</h3> 
            <button type="button" id="sidebarCollapse" class="btn sidebar-toggle-btn"> <i class="fas fa-align-left"></i>
            </button>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="{{ route('verify_email') }}" class="nav-items-style " id="m1"> <i class="fa fa-users" aria-hidden="true"></i>  <span>Email accounts</span> 
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" class="nav-items-style " id="m1"> <i class="far fa-address-card"></i>  <span>My Contacts</span> 
                </a>
            </li>
            <li class="temp1">
                <div class="btn-group dropright ">
                    <a type="" class="dropdown-toggle new-bt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="m2"> <i class="far fa-envelope"></i><span>Send Direct Message</span> 
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item new-drop" href="{{ route('compose_message') }}"><i class="fa fa-envelope"></i></i>Compose Message</a>  <a class="dropdown-item new-drop" href="{{ route('sent_messages') }}"><i class="far fa-paper-plane"></i>Sent Messages</a>
                    </div>
                </div>
            </li>
            <li>
                <a href="{{ route('sent_campaigns') }}" class="nav-items-style " id="m3"> <i class="fas fa-paper-plane"></i>  <span>Create Campaign</span> 
                </a>
            </li>
            <li>
                <a href="{{ route('templates') }}" class="nav-items-style" id="m4"> <i class="far fa-newspaper"></i>  <span>My Templates</span> 
                </a>
            </li>
              <li>
                <a href="{{ route('email_template_list') }}" class="nav-items-style" id="m4"> <i class="far fa-newspaper"></i>  <span>Email Templates</span> 
                </a>
            </li>
            <!-- <li>
                <a href="{{ route('email_template_list') }}" class="nav-items-style" id="m4"> <i class="fa fa-list" aria-hidden="true"></i>  <span>All Email Templates</span> 
                </a>
            </li> -->
            <li>
                <a href="{{ route('unsubscribed') }}" class="nav-items-style"> <i class="fas fa-comment-slash"></i>  <span>Unsubscribed List</span> 
                </a>
            </li>
            <li>
                <a href="{{ route('suppression') }}" class="nav-items-style" id="m6"> <i class="fas fa-ban"></i>  <span>Suppression List</span> 
                </a>
            </li>
            <li>
                <a href="{{ route('notifications') }}" class="nav-items-style" id="m7"> <i class="far fa-bell"></i>  <span>Notifications</span> 
                </a>
            </li>
        </ul>
    </nav>
    @yield('content')

</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{ URL('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://momentjs.com/downloads/moment-timezone.js"></script>
    <script src="https://momentjs.com/downloads/moment-timezone-with-data.js"></script>
    <script language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>

    <script src="{{ URL('public/assets/js/custom.js') }}"></script>
    <script src="{{ URL('public/js/jquery_popconfirm.js') }}"></script>
    <script src="{{ URL('public/assets/plugins/alert/alertify.min.js') }}"></script>
    <script src="{{ URL('public/assets/plugins/holdon/HoldOn.min.js') }}"></script>
    {{-- <script src="{{ URL('public/assets/plugins/recorder.js') }}"></script>
    <script src="{{ URL('public/assets/js/record.js') }}"></script> --}}
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{ URL('public/assets/js/script.js') }}"></script> 
    
   

    <script>
        $(document).ready(function(){
            $('.parsley').parsley();

            //date
            $('#datetimepicker').datetimepicker({ 
                format: "yyyy-mm-dd HH:MM:00",
                datepicker: {
                disableDates:  function (date) {
                // allow for today
                 const currentDate = new Date().setHours(0,0,0,0);
                 return date.setHours(0,0,0,0) >= currentDate ? true : false;
                }},
                footer: true, 
                modal: true 
            });
            //eo
            
        });

        function success(msg){
            alertify.set('notifier','position', 'top-right');
            alertify.success(msg);
        }
        function error(msg){
            alertify.set('notifier','position', 'top-right');
            alertify.error(msg);
        }

        @if(Session::has('error'))
            $(document).ready(function(){
                error('{{ Session::get("error") }}');
            });
        @endif

        @if(Session::has('success'))
            $(document).ready(function(){
                success('{{ Session::get("success") }}');
            });
        @endif
    </script>
      {{-- Today  --}}
    
    <script src="{{ URL('public/assets/js/Sortable.js') }}"></script>
    <script src="{{ URL('public/assets/js/prettify.js') }}"></script>
    <script src="{{ URL('public/assets/js/run_prettify.js') }}"></script>
    <script src="{{ URL('public/assets/js/app.js') }}"></script>
  </body>
</html>