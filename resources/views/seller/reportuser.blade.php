<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel = "stylesheet" href="{{ asset('css/seller/sellerProductStyle.css') }}">
</head>
<body>
    <nav>
        <ul>
            <li>
                 <a href="{{ route('sellerprofile') }}" class="logo">
                    <i class="fas fa-store"></i>
                    <img src="{{ asset('default_pics/logoS_2.png') }}" alt="">      
                 </a>
            </li>
            <li>
                <a href="{{ route('sellerprofile') }}" >
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
                <a href="{{ route('sellerorder') }}"  class="selectedNav">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> Orders </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sellerexchange') }}">
                    <i class="fas fa-check-to-slot"></i>
                    <span class="nav-item"> Exchanges </span>
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
        <h1>Orders</h1>

        <div class="icons">
            @if ($userdata->prof_pic !== 'default_pics/default_prof_pic.jpg')
                <img src="{{ asset('storage/custom_prof_pics/' . $userdata->prof_pic) }}" alt="{{ $userdata->first_name }} {{ $userdata->last_name }}" >
            @else
                <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="{{ $userdata->first_name }} {{ $userdata->last_name }}" >
            @endif
            <span>{{ $userdata->first_name }} {{ $userdata->last_name }}</span>
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

    <section class="ordExch" style="margin-left: 10%">
        <form action="{{ route('report.send') }}" method="POST">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
            <div class="mt-4">
                <div class="col-md-6">
                    @csrf
                    <h5>Reason for Report</h5>
                    @if ($type === 'order')
                        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                        <input type="hidden" name="user_id" value="{{ $order->buyer_id }}">
                        <input type="hidden" name="way" value="order">
                    @elseif ($type === 'exchange')
                        <input type="hidden" name="exchange_id" value="{{ $exchange->exchange_id }}">
                        <input type="hidden" name="user_id" value="{{ $exchange->buyer_id }}">
                        <input type="hidden" name="way" value="exchange">
                    @elseif ($type === 'review')
                        <input type="hidden" name="prod_id" value="{{ $review->prod_id }}">
                        <input type="hidden" name="user_id" value="{{ $review->user_id }}">
                        <input type="hidden" name="way" value="review">
                    @endif
                    <div class="form-group">
                        <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
                        <span class="text-danger">@error('content') {{$message}} @enderror</span>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">Submit Report</button>
                    </div>
                </div>
            </div>
        </form>

        <br>

        <div class="col-md-6">
            @if ($type === 'order')
                <a href="{{ url('/seller/orderdetails/'.$order->order_id) }}" class="btn btn-danger">Back</a>
            @elseif ($type === 'exchange')
                <a href="{{ url('/seller/exchangedetails/'.$exchange->exchange_id) }}" class="btn btn-danger">Back</a>
            @elseif ($type === 'review')
                <a href="{{ url('/seller/product/reviews/'.$review->prod_id) }}" class="btn btn-danger">Back</a>
            @endif
        </div>
    </section>
<script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
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