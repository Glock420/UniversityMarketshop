<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    <form action="{{ route('save.admin.details') }}" method="POST" enctype="multipart/form-data">
        @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif
    <div class = "details_container">
        @csrf
    <h2> Profile Details </h2><br>
    <div class="flex-container">
        <div class="detailsBox">
                    <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
                        <div class="input-box">
                            <label for="first_name">First Name</label><br>
                            <input type="text" id="first_name" name="first_name" value="{{ $userdata->first_name }}" required>
                            <span class="text-danger">@error('first_name') {{$message}} @enderror</span>
                        </div>
                        <div class="input-box">
                            <label for="last_name">Last Name</label><br>
                            <input type="text" id="last_name" name="last_name" value="{{ $userdata->last_name }}" required>
                            <span class="text-danger">@error('last_name') {{$message}} @enderror</span>
                        </div>
                        <div class="input-box">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ $userdata->email }}" required>
                            <span class="text-danger">@error('email') {{$message}} @enderror</span>
                        </div> 
                    <div class="save">
                        <button type="submit" class="btn btn-danger" style="font-size: 85%; width:40%;">Save</button>
                    </div>      
        </div>  
        <div class="ppBox">
            @if ($userdata->prof_pic !== 'default_pics/default_prof_pic.jpg')
                <img src="{{ asset('storage/custom_prof_pics/' . $userdata->prof_pic) }}" alt="Profile Picture" id="preview-image" > <br>
            @else
                <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="Default Profile Picture" id="preview-image" > <br>
            @endif
                <br>    
            <label for="file">Select Image</label><br>
            <input type="file" name="prof_pic" id="prof_pic" accept="image/*">
            <span class="text-danger">@error('prof_pic') {{$message}} @enderror</span>
        </div>
    </div> 
    </div>  
    </form> 
    
    <div class="changePass">
        <form action="{{ route('change.password') }}" method="POST">
                    @csrf
                    @if(Session::has('success-pass'))
                        <div class="alert alert-success">{{ Session::get('success-pass') }}</div>
                    @elseif(Session::has('fail-pass'))
                        <div class="alert alert-danger">{{ Session::get('fail-pass') }}</div>
                    @endif
            <h4>Change Password</h4>
                <div class="pass">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" value="{{ old('current_password') }}">
                    <span class="text-danger">@error('current_password') {{ $message }} @enderror</span>
                </div>
                <div class="pass">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" value="{{ old('new_password') }}">
                    <span class="text-danger">@error('new_password') {{ $message }} @enderror</span>
                </div>
                <div class="pass">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}">
                    <span class="text-danger">@error('new_password_confirmation') {{ $message }} @enderror</span>
                </div>
                    <br>
                <button type="submit" class="btn btn-danger" style="font-size: 100%; width:50%;">Change Password</button>
                    <br>
        </form> 
    </div>
    
 
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const profPicInput = document.getElementById('prof_pic');
    const previewImage = document.getElementById('preview-image');

    const resetProfilePic = () => {     //resets the input to default state
        profPicInput.value = '';
    };

    window.addEventListener('beforeunload', resetProfilePic);       //reset on page refresh (beforeunload event)

    profPicInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if(file)
        {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
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