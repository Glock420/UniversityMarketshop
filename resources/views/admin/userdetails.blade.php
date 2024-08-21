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
         @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <form>
        <div class = "details_container">
            @csrf
            @if ($userdetails->type === 'BUYER')
                <h2>{{ $userdetails->first_name }} {{ $userdetails->last_name }}, Profile Details</h2>
            @elseif ($userdetails->type === 'SELLER')
                <h2>{{ $userdetails->org_name }}, Profile Details</h2> 
            @endif
            <br>
        <div class="flex-container">
            <div class="detailsBox">
                        <input type="hidden" name="user_id" value="{{ $userdetails->user_id }}">
                        @if ($userdetails->type === 'SELLER')  
                            <div class="input-box">
                                <label for="org_name">Organization Name</label>
                                <input type="text" id="org_name" name="org_name" value="{{ $userdetails->org_name }}" readonly disabled>
                            </div>
                        @endif  
                            <div class="input-box">
                                <label for="first_name">First Name</label><br>
                                <input type="text" id="first_name" name="first_name" value="{{ $userdetails->first_name }}" readonly disabled>
                            </div>
                            <div class="input-box">
                                <label for="last_name">Last Name</label><br>
                                <input type="text" id="last_name" name="last_name" value="{{ $userdetails->last_name }}" readonly disabled>
                            </div>
                            <div class="input-box">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ $userdetails->email }}" readonly disabled>
                            </div> 
                            <div class="input-box">
                                <label for="type">Type</label>
                                <input type="text" id="type" name="type" value="{{ $userdetails->type }}" readonly disabled>                        
                            </div> 
                        @if ($userdetails->type === 'SELLER')
                            <div class="input-box">
                                <label for="chat_link">Facebook/Messenger Chat Link</label>
                                <input type="text" id="chat_link" name="chat_link" value="{{ $userdetails->chat_link }}" readonly disabled>
                            </div>
                            <div class="input-box">
                                <label for="gcash_no">GCash Number</label>
                                <input type="text" id="gcash_no" name="gcash_no" value="{{ $userdetails->gcash_no }}" readonly disabled>
                            </div>
                        @endif
                        <div class="save">
                        <a href="{{ route('userlist') }}" class="btn btn-dark">Back</a><br>
                        @if ($userdetails->is_disabled === 0)
                            @if ($warnCount >= 3)
                                <a class="Btn" disabled>Disable</a>
                            @else
                                <a href="{{ url('/admin/userlist/disable/'.$userdetails->user_id) }}" class="btn btn-danger">Disable</a>
                            @endif
                        @elseif ($userdetails->is_disabled === 1 && $warnCount < 3)
                            <a href="{{ url('/admin/userlist/enable/'.$userdetails->user_id) }}" class="btn btn-danger">Enable</a>
                        @endif
                        </div>      
            </div>  
            <div class="ppBox">
                    @if ($userdetails->prof_pic !== 'default_pics/default_prof_pic.jpg')
                        <img src="{{ asset('storage/custom_prof_pics/' . $userdetails->prof_pic) }}" alt="Profile Picture" >
                    @else
                        <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="Default Profile Picture" >
                    @endif
                    <br>    
                <label>Profile Picture</label><br>
            </div>
        </div> 
        </div>  
        </form>
        <br>
        <hr>
        <br>
        <form><div class="container">
            @if ($userdetails->type === 'BUYER')
                <h4>{{ $userdetails->first_name }} {{ $userdetails->last_name }} Warning Tickets</h4>
            @elseif ($userdetails->type === 'SELLER')
                <h4>{{ $userdetails->org_name }} Warning Tickets</h4>
            @endif
            @if ($warnCount >= 3)
                <b style="color: #9A0D13;">Note: This account is now permanently deactivated.</b>
            @else
                <b style="color: #9A0D13;">Note: This account will be permanently deactivated if it reaches 3 warnings.</b>
            @endif
            <div class="mt-4 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket</th><br>
                            <th>Reason for Warning</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowNumber = 1;
                        @endphp
                        @forelse ($warns as $warn)
                        <tr>
                            <td>{{ $rowNumber }}</td>
                            <td>{{ $warn->content }}</td>
                        </tr>
                        @php
                            $rowNumber++;
                        @endphp
                        @empty
                        <tr>
                            <td colspan="2">No tickets currently.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div></form>
        <br>
        @if ($warnCount < 3)
            <hr>
            <br>
            <form action="{{ route('send.ticket') }}" method="POST">
                @if ($userdetails->type === 'BUYER')
                    <h4>Issue Ticket to {{ $userdetails->first_name }} {{ $userdetails->last_name }}</h4>
                @elseif ($userdetails->type === 'SELLER')
                    <h4>Issue Ticket to {{ $userdetails->org_name }}</h4>
                @endif
                <div class="mt-4">
                    <div class="col-md-12">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $userdetails->user_id }}">
                        <div class="row d-flex align-items-center">
                            <div>
                                Reason for Ticket:
                            </div>
                            <div class="col-md-8">
                                <select id="content" name="content" class="form-control" required>
                                    <option disabled selected>Choose Reason</option>
                                    <option>Inappropriate Content in your Profile. Remove or change immediately!</option>
                                    <option>Your product/s contain inappropriate content. Remove or change immediately!</option>
                                    <option>You have been warned for misconduct!</option>
                                </select>
                                <span class="text-danger">@error('content') {{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">Send Ticket</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</section>     

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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