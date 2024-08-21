<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel = "stylesheet" href="{{ asset('css/seller/sellerProfStyle.css') }}">
</head>


<body>

    <nav>
        <ul>
            <li>
                 <a href="{{ route('sellerprofile') }}" class="logo">
                    <i class="fas fa-store"></i>
                    <img src="{{ asset('default_pics/logoS.png') }}" alt="">      
                 </a>
            </li>
            <li>
                <a href="{{ route('sellerprofile') }}" class="selectedNav">
                    <i class="fas fa-user"></i>
                    <span class="nav-item"> My Account </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sellerproduct') }}">
                    <i class="fas fa-shirt"></i>
                    <span class="nav-item"> My Products </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sellerorder') }}">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> Incoming Orders </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sellerexchange') }}">
                    <i class="fas fa-check-to-slot"></i>
                    <span class="nav-item"> Incoming Exchanges </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sales.report') }}">
                    <i class="fas fa-user"></i>
                    <span class="nav-item"> Sales Report </span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"  class="logout">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span class="nav-item"> Logout </span>
                </a>
            </li>
        </ul>
    </nav>

    <header>
        <h1>Profile</h1>

        <div class="icons">
            @if ($userdata->prof_pic !== 'default_pics/default_prof_pic.jpg')
                <img src="{{ asset('storage/custom_prof_pics/' . $userdata->prof_pic) }}" alt="{{ $userdata->first_name }} {{ $userdata->last_name }}" >
            @else
                <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="{{ $userdata->first_name }} {{ $userdata->last_name }}" >
            @endif
            <span>{{ $userdata->first_name }} {{ $userdata->last_name }}</span>
        <!--<img src="#" alt="pp">
            <a href="#" class="fas fa-user"></a>
            <label for="fas fa-user">Admin</label>-->
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
        </div>
    </header>
   
<!----------------------CONTENT--------------------------->
<section class="details">
<div class = "container">
        <form action="{{ route('save.seller.address') }}" method="POST" enctype="multipart/form-data">
        @csrf
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <div class = "details_container">
                <h2>Seller New Address</h2> 
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
                    <a href="{{ route('selleraddressbook') }}" class="btn btn-dark" style="width: 20%">Back</a>
                    <button type="submit" class="btn btn-danger">Add Address</button>
                </div>      
            </div>  
        </div>  
        </form>
</div>
</section>

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

    </style>
</body>
</html>
