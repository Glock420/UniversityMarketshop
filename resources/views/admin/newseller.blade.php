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


    <link rel = "stylesheet" href="{{ asset('css/admin/styleAdUserMod.css') }}">
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
                <a href="{{ route('adminprofile') }}">
                    <i class="fas fa-user"></i>
                    <span class="nav-item"> Profile </span>
                </a>
            </li>
            <li>
                <a href="{{ route('userlist') }}"  class="selectedNav">
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
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span class="nav-item"> Logout </span>
                </a>
            </li>
        </ul>
    </nav>

    <header>
        <h1>Users</h1>

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
        <form action="{{ route('save.new.seller') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <div class = "details_container">
            <h2>Add New Seller</h2>
            <br>
        <div class="flex-container">
            <div class="detailsBox">
                            <div class="input-box">
                                <label for="org_name">Organization Name</label>
                                <input type="text" class="form-control" id="org_name" name="org_name" required>
                                <span class = "text-danger">@error('org_name') {{$message}} @enderror</span>                            
                            </div> 
                            <div class="input-box">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <span class = "text-danger">@error('first_name') {{$message}} @enderror</span>
                            </div>
                            <div class="input-box">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                <span class = "text-danger">@error('last_name') {{$message}} @enderror</span>
                            </div>
                            <div class="input-box">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <span class = "text-danger">@error('email') {{$message}} @enderror</span>
                            </div> 
                            <div class="input-box">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class = "text-danger">@error('password') {{$message}} @enderror</span>               
                            </div> 
                            <div class="input-box">
                                <label for="chat_link">FB/Messenger Chat Link</label>
                                <input type="text" class="form-control" id="chat_link" name="chat_link" required>
                                <span class = "text-danger">@error('chat_link') {{$message}} @enderror</span>
                            </div>
                            <div class="input-box">
                                <label for="gcash_no">GCash Number</label>
                                <input type="text" class="form-control" id="gcash_no" name="gcash_no" required>
                                <span class = "text-danger">@error('gcash_no') {{$message}} @enderror</span>
                            </div>

                        <div class="save">
                            <a href="{{ route('userlist') }}" class="btn btn-dark" style="width: 100px;">Back</a><br>
                            <button type="submit" class="btn btn-danger"  style="width: 100px;">Save</button><br><br>
                        </div>      
            </div>  
            <div class="ppBox">
                    <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="Profile Picture" id="preview-image">
                    <label for="file">Select Image</label><br>
                    <input type="file" id="prof_pic" name="prof_pic">
                    <span class = "text-danger">@error('prof_pic') {{$message}} @enderror</span>
            </div>
        </div> 
        </div>  
        </form>
    </div>
</section>     

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
