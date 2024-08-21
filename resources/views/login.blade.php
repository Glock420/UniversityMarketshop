<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleHome.css') }}">
</head>

<body>

<header>
    <input type="checkbox" name="" id="toggler">
    <label for="toggler" class="fas fa-bars"></label>

    <a href="{{ route('home') }}" class="logo"> UNIVERSITY MARKETSHOP </a>

        <nav class="navbar">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('productcatalog') }}">Products</a>
                <a href="{{ route('ordertracking') }}">Order Tracking</a>
                <a href="{{ route('login') }}">Exchange Requests</a>
        </nav>

        <div class="icons">
            <a href="{{ route('login') }}" class="fas fa-bell"></a>
            @php
                $notifications = collect([]);
            @endphp
            <a href="#" class="fas fa-search" id="search-link"></a>
                    <div id="search-box">
                    <form id="search-form" action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" id="search-input" placeholder="Search products...">
                        <button type="submit">Search</button>
                    </form>
                    </div>
            <a href="{{ route('login') }}" class="fas fa-shopping-cart"></a>
            <a href="{{ route('login') }}" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" cl class="fas fa-user"></a>
        </div>
</header>
    

<section>

        <div class="login-box">
            <form method="POST" action="{{ route('login.user') }}">
                @csrf
                <h1><b>LOGIN</b></h1>
                <br>
                @if(Session::has('fail'))
                    <div class = "alert alert-danger">{{Session::get('fail')}}</div>
                @endif
                

                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                    <label>Email</label>
                        </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <label>Password</label>
                        </div>
                <!-- <div class="forgot-pass">
                    <a href="#">Forgot Password?</a>
                        </div> -->
                <button type="submit">Login</button>

                <div class="register">
                    <p>Don't have an account? <a href="{{ route('register') }}">   Sign Up</a></p>
                </div>
            </form>
        </div>
</section>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search-link").click(function(e) {
                e.preventDefault(); // Prevent the link from navigating

                $("#search-box").toggle();
            });
        });

        $(document).ready(function() {
            $('#search-link').click(function(e) {
                e.preventDefault();
                $('#search-box').toggleClass('active');
            });
        });

        $(document).ready(function () {
            const $notificationBell = $('#notificationBell');
            const $notificationDropdown = $('.notification-dropdown');
            const $alertSymbol = $('#alertSymbol');
            const $bellIcon = $('#bellIcon');
            let notificationBoxOpen = false;

            function checkUnreadNotifications() {
                const notificationCount = {!! $notifications->count() !!};
                if (notificationCount > 0) {
                    $alertSymbol.show();
                    $bellIcon.hide();
                } else {
                    $alertSymbol.hide();
                    $bellIcon.show();
                }
            }

            checkUnreadNotifications();

            $notificationBell.on('click', function (e) {
                e.stopPropagation();
                if (!notificationBoxOpen) {
                    $notificationDropdown.toggle();
                    markNotificationsAsRead();
                    notificationBoxOpen = true;
                } else {
                    $notificationDropdown.hide();
                    notificationBoxOpen = false;
                }
            });

            $(document).on('click', function () {
                $notificationDropdown.hide();
                notificationBoxOpen = false;
            });
        });

        function markNotificationsAsRead() {
            $.ajax({
                url: '{{ route('mark.notif') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function () {
                    console.log('Notifications marked as read.');
                },
                error: function () {
                    // Error handling
                }
            });
        }
    </script>

    <style>
        .notification-dropdown {
            position: absolute;
            background: white;
            width: 300px;
            height: auto;
            max-height: 300px; /* Set a maximum height, you can adjust this as needed */
            border: 1px solid #ccc;
            padding: 10px;
            display: none;
            right: 170px;
            top: 55px;
            z-index: 1;
            overflow-y: auto; /* Add a scrollbar for overflow */
        }
    </style>
</body>
</html>



<!-- <!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('home') }}">Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>    
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('productcatalog') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Customer Care</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
            </ul>     
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-search"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="#" class="cart"><i class="fa fa-cart-shopping"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login.user') }}">
                            @csrf
                            @if(Session::has('fail'))
                                <div class = "alert alert-danger">{{Session::get('fail')}}</div>
                            @endif
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">LOGIN</button>
                            <div class="mt-3">
                                <a href="{{ route('register') }}">Create account</a>
                            </div>
                            <div class="mt-2">
                                <a href="#">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> -->
