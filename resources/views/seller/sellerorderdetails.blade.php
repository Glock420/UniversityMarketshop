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

<section class="ordExch">
        @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif
<div class = "ordContainer">

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('sellerorder') }}" class="btn btn-danger">Back</a>
            </div>
            <div>
                Order ID: {{ $order->order_id }}  |  {{ $order->status }}
            </div>
        </div>
        <hr>
        <div class="address">
            <h4>Delivery Address</h4>
            {{ $order->phone }} <br>
            {{ $order->street_add }} <br>
            {{ $order->city }}, {{ $order->province }}, {{ $order->postal }} <br>
            Date Ordered: {{ $order->date }} <br>
            @if ($releasedate) Parcel Release Date: {{ $releasedate }} <br> @endif
            @if ($order->receive_date) Date Received: {{ $order->receive_date }} <br> @endif
        </div>
        <hr>
        <div class="d-flex align-items-center">
            <div class="col-md-6"><h5><b>Buyer: </b>{{ $buyer->first_name }} {{ $buyer->last_name }}</h5></div>
            <div class="col-md-6 text-right"><a href="{{ url('/seller/orderdetails/reportuser/'.$order->order_id.'/order') }}" class="btn btn-danger">Report</a></div>
        </div>
        <hr>
        <div class="order-items">
            @foreach ($orderitems as $orderitem)
                <a href="{{ url('/seller/product/editproduct/'.$orderitem->prod_id) }}" style="text-decoration: none; color: black;">
                <div class="order-item-row d-flex justify-content-between">
                    <div class="order-item-details">
                        @if ($orderitem->prod_image !== 'default_pics/default_prod_pic.jpg')
                            <img src="{{ asset('storage/custom_prod_pics/'.$orderitem->prod_image) }}" alt="Product Image" style="width: 100px; height: 100px;">
                        @else
                            <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 100px; height: 100px;">
                        @endif
                        <div class="product-info">
                            {{ $orderitem->prod_name }} <br>
                            @if ($orderitem->color && $orderitem->size)
                                {{ $orderitem->color }} - {{ $orderitem->size }}
                            @elseif ($orderitem->color)
                                {{ $orderitem->color }}
                            @elseif ($orderitem->size)
                                {{ $orderitem->size }}
                            @endif
                        </div>
                    </div>
                    <div class="product-quantity">
                        <p>Quantity: {{ $orderitem->quantity }}</p>
                    </div>
                </div>
                </a>
                <hr>
            @endforeach
        </div>
        @if ($order->status === 'UNPAID')
            @if ($order->ref_no)
                <div class="d-flex justify-content-between align-items-center">
                    <div class="col-md-6">
                        <b>GCash Reference Number: </b>{{ $order->ref_no }}
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/seller/orderdetails/orderapprove/'.$order->order_id) }}" class="btn btn-warning">Approve</a>
                    </div>
                </div>
                <hr>
            @else
                <div>
                    <b>Please wait for customer to send GCash reference number proof of payment.</b>
                </div>
                <hr>
            @endif
        @elseif ($order->status === 'CANCELED')
            <div>
                <b>Reason for Cancellation:</b>
            </div>
            <div>
                {{ $order->cancel_reason }}
            </div>
            <hr>
        @elseif ($order->status === 'ON THE WAY')
            <div class="d-flex align-items-center">
                <b>GCash Reference Number: </b>{{ $order->ref_no }}
            </div>
            <div>
                <form method="POST" action="{{ route('seller.order.track') }}">
                @csrf
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                        @if ($order->track_num)
                            <b>Tracking Number: </b>
                            <input style="margin-left: 1%" type="text" id="track_num" name="track_num" value="{{ $order->track_num }}">
                            <button style="margin-left: 1%" type="submit" class="btn btn-danger">Update</button>
                        @else
                            <b>Enter Tracking Number: </b>
                            <input style="margin-left: 1%" type="text" id="track_num" name="track_num">
                            <button style="margin-left: 1%" type="submit" class="btn btn-danger">Post</button>
                        @endif
                    </div>
                    <div><span class="text-danger">@error('track_num') {{$message}} @enderror</span></div>
                </form>
            </div>
            <hr>
        @elseif ($order->status === 'COMPLETED')
            <div>
                <b>Customer has received the parcel.</b>
            </div>
            <hr>
        @endif
        <div class="order-total text-right">
            <p>Merchandise Total: ₱{{ number_format($merchtotal, 2) }}</p>
            <p>Shipping Fee: ₱{{ number_format($order->ship_fee, 2) }}</p>
            <p>Total: ₱{{ number_format($order->total, 2) }}</p>
        </div>
    </div>
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