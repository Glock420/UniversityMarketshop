<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel = "stylesheet" href="{{ asset('css/styleOrderDetails.css') }}">
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
                <a href="{{ route('dashboard') }}" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" class="fas fa-user"></a>
                <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>

            </div>
    </header>


<div class="ordExch">
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
<div class = "ordContainer">

    <div class="container mt-4">
        <h2>Order Details</h2>
        <br>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-danger buttones">Back</a>
            </div>
            <div>
                <p class="stats">Order ID: {{ $order->order_id }}  |  {{ $order->status }}</p>
            </div>
        </div>
        <hr>
        <div class="address">
            <h4><b>Delivery Address</b></h4>
            {{ $userdata->first_name }} {{ $userdata->last_name }} <br>
            {{ $order->phone }} <br>
            {{ $order->street_add }} <br>
            {{ $order->city }}, {{ $order->province }}, {{ $order->postal }} <br>
            Date Ordered: {{ $order->date }} <br>
            @if ($releasedate) Parcel Release Date: {{ $releasedate }} <br> @endif
            @if ($order->receive_date) Date Received: {{ $order->receive_date }} <br> @endif
        </div>
        <hr>
        <div class="ordORG">
            <div class="order-status" style="display: none;">{{ $order->status }}</div>
            <div class="d-flex align-items-center">
                <h4 style="margin-bottom: 0%; margin-right: 1%;">{{ $order->seller->org_name }}</h4>
                <a href="{{ $order->seller->chat_link }}" target="_blank" class="btn btn-dark buttones">Chat</a>
            </div>
        </div>
        <hr>
        <div class="order-items">
            @foreach ($orderitems as $orderitem)
                <a href="{{ url('/catalog/fulldetails/'.$orderitem->prod_id) }}" style="text-decoration: none; color: black;">
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
            <div class="row d-flex align-items-center">
                <div class="col-md-6 references">
                    <form method="POST" action="{{ route('order.unpaid') }}">
                    @csrf
                        <div>
                            Pay the amount via GCash to this number: <b>{{ $seller->gcash_no }}</b>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                            @if ($order->ref_no)
                                GCash Reference Number:
                                <input style="margin-left: 1%" type="text" id="ref_no" name="ref_no" value="{{ $order->ref_no }}">
                                <button style="margin-left: 1%" type="submit" class="btn btn-secondary buttones">Update</button>
                            @else
                                Enter GCash Reference Number:
                                <input style="margin-left: 1%" type="text" id="ref_no" name="ref_no">
                                <button style="margin-left: 1%" type="submit" class="btn btn-secondary buttones">Send</button>
                            @endif
                        </div>
                        <div><span class="text-danger">@error('ref_no') {{$message}} @enderror</span></div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ url('/dashboard/orderdetails/cancelpage/'.$order->order_id) }}" class="btn btn-danger buttones">Cancel Order</a>
                </div>
            </div>
            <hr>
        @elseif ($order->status === 'CANCELED')
            <div>
                <b>Reason for Cancellation:</b>
            </div>
            <div>
                {{ $order->cancel_reason }}
            </div>
            <hr>
        @elseif ($order->status === 'ON THE WAY')
            @if ($order->track_num)
                <div class="row d-flex align-items-center" style="font-size:150%;">
                    <div class="col-md-6">
                        <b>Parcel Tracking Number: </b>{{ $order->track_num }}
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/dashboard/orderdetails/complete/'.$order->order_id) }}" class="btn btn-danger" style="font-size:100%;">Order Received</a>
                    </div>
                </div>
                <hr>
            @else
                <div>
                    <b>Your parcel is being shipped and will be on the way soon.</b>
                </div>
                <hr>
            @endif
        @elseif ($order->status === 'COMPLETED')
            <div class="row d-flex align-items-center" style="font-size:150%;">
                <div class="col-md-6">
                    <b>Parcel has been delivered.</b>
                </div>
                <div class="col-md-6 text-right">
                    @if ($dateDifference >= 2)
                        <button class="btn btn-danger" style="font-size:100%;" disabled>Request an Exchange</button>
                    @elseif ($order->has_exchange === 1)
                        <button class="btn btn-danger" style="font-size:100%;" disabled>Request an Exchange</button>
                    @else
                        <a href="{{ url('/exchange/requestpage/'.$order->order_id) }}" class="btn btn-danger" style="font-size:100%;">Request an Exchange</a>
                    @endif
                </div>
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
</div>

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