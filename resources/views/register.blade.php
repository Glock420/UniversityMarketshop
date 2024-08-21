<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleRegister.css') }}">
</head>
<body>

<!----------NAV BAR-------------->
    <header>    
        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>

        <a href="{{ route('home') }}" class="logo"> UNIVERSITY MARKETSHOP </a>

            <nav class="navbar">
                <a href="#">How to Order</a>
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


<!----------BODY-------------->
    <section>
    <div class="form-box">
            <div class="form-value">

                <form method="POST" action="{{ route('register.buyer') }}">
                @csrf
                @if(Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                <h1><b>CREATE AN ACCOUNT</b></h1>
                   
                <div class="boxInput">
                    <input type="text" id="first_name" name="first_name" autocapitalize="words" required>
                    <label for="first_name">First Name</label>
                    <span class = "text-danger">@error('first_name') {{$message}} @enderror</span>
                </div>

                <div class="boxInput">
                    <input type="text" id="last_name" name="last_name" autocapitalize="words" required>
                    <label for="last_name">Last Name</label>
                    <span class = "text-danger">@error('last_name') {{$message}} @enderror</span>
                </div>

                <div class="boxInput">
                    <input type="text" id="email" name="email" required>
                    <label for="username">Email</label>
                    <span class = "text-danger">@error('email') {{$message}} @enderror</span>
                </div>

                <div class="boxInput">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                    <span class = "text-danger">@error('password') {{$message}} @enderror</span>
                </div>

                <button type="submit">Sign Up</button>

                <div class="login">
                                <p>Already have an account? <a href="{{ route('login') }}">   Login</a></p>
                            </div>
                </form>


        </div>
    </div>
</section>

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
