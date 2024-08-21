<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>New Address</title>
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
                <a href="#">How to Order</a>
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
        <form action="{{ route('save.seller.address') }}" method="POST" enctype="multipart/form-data">
        @csrf
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <div class = "details_container">
                <h2>New Address</h2> 
                <br>
            
            <div class="detailsBox">
                <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
                        <div class="form-group">
                            <label for="province">Province</label>
                            <select class="form-control" id="province" name="province" required>
                                <option value="" disabled selected>Select Province</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">@error('province') {{ $message }} @enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <select class="form-control" id="city" name="city" required>
                                <option value="" disabled selected>Select City</option>
                            </select>
                            <span class="text-danger">@error('city') {{ $message }} @enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="street_add">Street Address</label>
                            <input type="text" class="form-control" id="street_add" name="street_add" required>
                            <span class = "text-danger">@error('street_add') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="postal">Postal Code</label>
                            <input type="text" class="form-control" id="postal" name="postal" required>
                            <span class = "text-danger">@error('postal') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <span class = "text-danger">@error('phone') {{$message}} @enderror</span>
                        </div>
                        <br>
                <div class="addr"> 
                    <a href="{{ route('addressbook') }}" class="btn btn-dark" style="font-size: 80%; width:20%;">Back</a>
                    <button type="submit" class="btn btn-danger" style="font-size: 85%; width:40%;">Add New Address</button>
                </div>      
            </div>  
        </div>  
        </form>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Sample data for provinces and cities
        const provinceData = {
            "Negros Oriental": ["Dumaguete", "City 1B", "City 1C"],
            "Negros Occidental": ["Bacolod", "City 2B"],
            // Add more provinces and cities as needed
        };

        // Get references to the Province and City dropdown elements
        const provinceDropdown = $('#province');
        const cityDropdown = $('#city');

        // Function to populate the City dropdown based on the selected Province
        function populateCities() {
            const selectedProvince = provinceDropdown.val();
            const cities = provinceData[selectedProvince] || [];

            // Clear previous options
            cityDropdown.empty();

            // Add new options to the City dropdown
            cities.forEach(function(city) {
                cityDropdown.append($('<option>').text(city).val(city));
            });
        }

        // Initial population of City dropdown based on the default selected Province
        populateCities();

        // Handle changes in the selected Province
        provinceDropdown.on('change', function() {
            populateCities();
        });
    });

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