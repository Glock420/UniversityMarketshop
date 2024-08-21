<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel = "stylesheet" href="{{ asset('css/styleAccount.css') }}">
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
                <a href="{{ route('dashboard') }}" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" cl class="fas fa-user"></a>
                <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>
            </div>
    </header>

    <section>
<div class = "container">
	<div class = "account_container">
    <h2> MY ACCOUNT </h2>
            <div class="orderBox">
                <div class="status-filter row">
                    <h3 style="margin-right: 10%;">Order History</h3>

                    <div>
                        <select id="orderStatusFilter" class="form-control" onchange="filterOrders(this.value)">
                            <option value="All">All</option>
                            <option value="UNPAID">UNPAID</option>
                            <option value="ON THE WAY">ON THE WAY</option>
                            <option value="COMPLETED">COMPLETED</option>
                            <option value="CANCELED">CANCELED</option>
                        </select>
                    </div>
                </div>
                @if ($orders->count() > 0)
                @foreach ($orders as $order)
                <div class="orderItem">                   
                        <hr>
                        <br>
                        <div class="description">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="order-status" style="display: none;">{{ $order->status }}</div>
                                <div class="d-flex align-items-center">
                                    <h5 style="margin-bottom: 0; margin-right: 2%;">{{ $order->seller->org_name }}</h5>
                                    <a href="{{ $order->seller->chat_link }}" target="_blank" class="btn btn-dark">Chat</a>
                                </div>
                                <div>
                                    <p style="margin-bottom: 0;">Order ID: {{ $order->order_id }}  |  {{ $order->status }}</p>
                                </div>
                            </div>
                            <hr>
                            <a href="{{ url('/dashboard/orderdetails/'.$order->order_id) }}" style="text-decoration: none; color: black;">
                                <div class="order-items">
                                    @foreach ($order->orderItems as $orderItem)
                                    <div class="order-item-row d-flex justify-content-between align-items-center">
                                        <div class="order-item-details">
                                            @if ($orderItem->prod_image !== 'default_pics/default_prod_pic.jpg')
                                                <img src="{{ asset('storage/custom_prod_pics/'.$orderItem->prod_image) }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @else
                                                <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @endif
                                            <div class="product-info" style="color: white;">
                                                {{ $orderItem->prod_name }}<br>
                                                @if ($orderItem->color && $orderItem->size)
                                                    {{ $orderItem->color }} - {{ $orderItem->size }}
                                                @elseif ($orderItem->color)
                                                    {{ $orderItem->color }}
                                                @elseif ($orderItem->size)
                                                    {{ $orderItem->size }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-quantity" style="color: white;">
                                            Quantity: {{ $orderItem->quantity }}
                                        </div>
                                    </div>
                                    <hr>
                                    @endforeach
                                </div>
                                @if ($order->status === 'UNPAID')
                                    @if ($order->ref_no)
                                        <div>
                                            <b>GCash Reference Number: </b>{{ $order->ref_no }}
                                        </div>
                                        <hr>
                                    @else
                                        <div style="color: white;">
                                            <b>Click on me to send a GCash reference number as proof of payment.</b>
                                        </div>
                                        <hr>
                                    @endif
                                @elseif ($order->status === 'ON THE WAY')
                                    @if ($order->track_num)
                                        <div style="color: white;">
                                            <b>Parcel Tracking Number: </b>{{ $order->track_num }}
                                        </div>
                                        <hr>
                                    @else
                                        <div style="color: white;">
                                            <b>Your parcel is being shipped and will be on the way soon.</b>
                                        </div>
                                        <hr>
                                    @endif
                                @elseif ($order->status === 'COMPLETED')
                                    <div style="color: white;">
                                        <b>Parcel has been delivered.</b>
                                    </div>
                                    <hr>
                                @endif
                            </a>
                            <div class="order-total text-right">
                                Total: ₱{{ number_format($order->total, 2) }}
                            </div>
                        </div>
                        <br> 
                </div>
                <br><br>
                @endforeach
                @else
                    <p>No Orders at the moment or of this status.</p>
                @endif
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
		</div>
		<div class="details_container">
			<div class="details">
				<h3>Profile Details</h3>
                <p>
                    {{ $userdata->first_name }}<br>
                    {{ $userdata->last_name }}<br>
                    {{ $userdata->email }}<br>
                    <br><a href="{{ route('profile') }}" class="btn btn-danger editDelete">Edit Profile Details</a>
                </p>
                <br>
                <h3>Address Details</h3>
                @if($defaultaddress)
                <p>
                    {{ $defaultaddress->street_add }}<br>
                    {{ $defaultaddress->city }}, {{ $defaultaddress->province }}<br>
                    {{ $defaultaddress->postal }}<br>
                    [Default Address]<br>
                </p>
                @else
                <p>NO ADDRESSES</p>
                @endif
                <a href="{{ route('addressbook') }}" class="btn btn-danger editDelete">View Address Book</a>
                <br><br><br>
                <br><br><br>
                <h3><b>Warning Tickets</b></h3>
                <p>Tickets: {{ $warnCount }}</p>
                <a href="{{ route('ticketlist') }}" class="btn btn-danger editDelete">View Tickets</a>
			</div>
	</div>
</div>
</section>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.onload = function () {
            const currentStatus = window.location.pathname.split('/').pop();

            if(currentStatus)
                document.getElementById('orderStatusFilter').value = currentStatus;
        };

        function filterOrders(status) {
            window.location.href = '{{ route('dashboard') }}/' + status;
        }

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