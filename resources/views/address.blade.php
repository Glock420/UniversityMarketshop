<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel = "stylesheet" href="{{ asset('css/styleProfile.css') }}">
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
                <a href="{{ route('exchangelist') }}">Exchange Requests</a>
            </nav>

            <div class="icons">
                <a href="javascript:void(0);" id="notificationBell">
                    <i id="bellIcon" class="fa-solid fa-bell"></i>
                    <i id="alertSymbol" class="fa-solid fa-bell fa-bounce"></i>
                </a>
                <div class="notification-dropdown">
                    @if ($notifications->count() > 0)
                        @foreach ($notifications as $notification)
                            <a href="{{ $notification->quick_link }}" style="text-decoration: none; color: black;"><div class="notification-item" data-notify-id="{{ $notification->id }}">{{ $notification->content }}</div></a>
                            @if (!$loop->last)<hr>@endif
                        @endforeach
                    @else
                        No new notifications.
                    @endif
                </div>
                <a href="#" class="fas fa-search" id="search-link"></a>
                    <div id="search-box" style="display: none;">
                        <form id="search-form" action="{{ route('search') }}" method="GET">
                            <input type="text" name="q" id="search-input" placeholder="Search products...">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                <a href="{{ route('cart') }}" class="fas fa-shopping-cart"></a>
                <a href="{{ route('dashboard') }}" class="fas fa-user" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" cl class="fas fa-user"></a>
                <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>
            </div>
    </header>


 <!----------------------CONTENT--------------------------->

<div class = "container">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <div class = "details_container">
    
                <h2>Address</h2> 
            <br>
            <div class="detailsBox">
                @if ($addresses->count() > 0)
                <div class="address-grid mt-4">
                    @foreach ($addresses as $address)
                        <div class="address-box mb-4 p-3 border">
                            @if ($address->default)
                                <div class="default-address">[Default]</div>
                            @endif
                            <div class="street">{{ $address->street_add }}</div>
                            <div class="location">{{ $address->postal }}, {{ $address->city }}, {{ $address->province }}</div>
                            <div class="phone">{{ $address->phone }}</div>
                            <div class="actions mt-2">
                                @if ($userdata->is_disabled === 0)
                                    @if ($warnCount >= 3)
                                        <a class="btn btn-sm btn-dark editDelete" disabled>Edit</a><br><br>
                                    @else
                                        <a href="{{ url('/dashboard/addressbook/editaddress/'.$address->add_id) }}" class="btn btn-sm btn-dark editDelete">Edit</a><br><br>
                                    @endif
                                @endif
                                @if ($userdata->is_disabled === 0)
                                    @if ($warnCount >= 3)
                                        <a class="btn btn-sm btn-danger editDelete" disabled>Delete</a>
                                    @else
                                        <a href="{{ url('/seller/sellerprofile/addressbook/deleteaddress/'.$address->add_id) }}" class="btn btn-sm btn-danger editDelete">Delete</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                    <p>You have no Addresses</p>
                @endif

                <div class="addr"> 
                    <a href="{{ route('dashboard') }}" class="btn btn-dark" style="font-size: 80%; width:20%;">Back</a>
                    @if ($userdata->is_disabled === 0)
                        @if ($warnCount >= 3)
                            <a class="addrNew" disabled>Add New Address</a>
                        @else
                            <a href="{{ route('newaddress') }}" class="btn btn-danger" style="font-size: 80%; width: 35%;">Add New Address</a>
                        @endif
                    @endif
                </div>      
            </div>  
        </div>  
</div><!--container-->

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