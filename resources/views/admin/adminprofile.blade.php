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
    <link rel = "stylesheet" href="{{ asset('css/admin/styleAdmin.css') }}">
</head>
<body>
    <nav>
        <ul>
            <li>
                 <a href="{{ route('adminprofile') }}" class="logo">
                    <i class="fas fa-store"></i>
                    <img src="{{ asset('default_pics/logoS.png') }}" alt="">      
                 </a>
            </li>
            <li>
                <a href="{{ route('adminprofile') }}" class="selectedNav">
                    <i class="fas fa-user"></i>
                    <span class="nav-item"> Profile </span>
                </a>
            </li>
            <li>
                <a href="{{ route('userlist') }}">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> User List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('productlist') }}">
                    <i class="fas fa-check-to-slot"></i>
                    <span class="nav-item"> Product List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('categorylist') }}">
                    <i class="fas fa-list"></i>
                    <span class="nav-item"> Category List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"  class="logout">
                    <i class="fas fa-right-from-bracket"></i>
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

    @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif

<div class = "container">
    <form action="{{ route('save.admin.details') }}" method="POST" enctype="multipart/form-data">
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
                        <button type="submit" class="btn btn-danger">Save</button>
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
                <button type="submit" class="btn btn-dark">Change Password</button>
                    <br>
        </form> 
    </div>
    <br><br><br><br>
    <h2 style="color:white;">Change Shipping Fee</h2>
        <form action="{{ route('update.fee') }}" method="POST">
            @csrf
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="fee_id" name="fee_id" value="{{ $fee->fee_id }}">
                        <label for="fee" style="color:white;">Shipping Fee</label>
                        <input type="text" class="form-control" id="fee" name="fee" value="{{ $fee->fee }}" required>
                        <span class = "text-danger">@error('fee') {{$message}} @enderror</span>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="submit" class="btn btn-danger">Update Shipping Fee</button>
                    </div>
                </div>
            </div>
        </form>
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
</body>
</html>