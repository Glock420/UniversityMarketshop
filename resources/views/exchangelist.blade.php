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
            <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('productcatalog') }}">Products</a>
                <a href="{{ route('ordertracking') }}">Order Tracking</a>
                <a href="{{ route('exchangelist') }}" class="selectedNav">Exchange Requests</a>
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
    @endif
<div class = "ordContainer">
<div class = "account_container">
    <div class="orderBox">
        <h2>Your Exchange Requests</h2>
        <div class="row mt-3 mb-3 justify-content-end">
            <div>
                <div class="status-filter row">
                    <div style="margin-right: 20%;">
                        <select id="exchangeStatusFilter" class="form-control" onchange="filterExchanges(this.value)">
                            <option value="All">All</option>
                            <option value="PENDING">PENDING</option>
                            <option value="ON THE WAY TO SELLER">ON THE WAY TO SELLER</option>
                            <option value="ON THE WAY TO BUYER">ON THE WAY TO BUYER</option>
                            <option value="REJECTED">REJECTED</option>
                            <option value="CANCELED">CANCELED</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="container mt-4">
            <div class="exchanges">
                @if ($exchanges->count() > 0)
                    @foreach ($exchanges as $exchange)
                    <div class="exchange-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="exchange-status" style="display: none;">{{ $exchange->status }}</div>
                            <div class="statsExch d-flex align-items-center">
                                <h3 style="margin-bottom: 0; margin-right: 2%;">{{ $exchange->seller->org_name }}</h3>
                                <a href="{{ $exchange->seller->chat_link }}" target="_blank" class="btn btn-dark">Chat</a>
                            </div>
                            <div class="statsExch">
                                Exchange ID: {{ $exchange->exchange_id }} | {{ $exchange->status }}
                            </div>
                        </div>
                        <hr>
                        <a href="{{ url('/exchange/exchangedetails/'.$exchange->exchange_id) }}" style="text-decoration: none; color: black;">
                            <div class="exchItems">
                                @foreach ($exchange->exchangeItems as $exchangeItem)
                                    <div class="exchange-item-row d-flex justify-content-between align-items-center">
                                        <div class="exchange-item-details">
                                            @if ($exchangeItem->prod_image !== 'default_pics/default_prod_pic.jpg')
                                                <img src="{{ asset('storage/custom_prod_pics/'.$exchangeItem->prod_image) }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @else
                                                <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @endif
                                            <div class="product-info" style="color: white;">
                                                {{ $exchangeItem->prod_name }}<br>
                                                @if ($exchangeItem->color && $exchangeItem->size)
                                                    {{ $exchangeItem->color }} - {{ $exchangeItem->size }}
                                                @elseif ($exchangeItem->color)
                                                    {{ $exchangeItem->color }}
                                                @elseif ($exchangeItem->size)
                                                    {{ $exchangeItem->size }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-quantity" style="color: white;">
                                            Quantity: {{ $exchangeItem->quantity }}
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                            @if ($exchange->status === 'PENDING')
                                <div class="statsExch" style="color: white;">
                                    <b>Your request for exchange is under review.</b>
                                </div>
                            @elseif ($exchange->status === 'ON THE WAY TO SELLER')
                                <div style="font-size: 150%;" style="color: white;">
                                    <b>Please pack item/s in original packaging and ship out to seller's address and inform seller.</b>
                                </div>
                            @endif
                        </a>
                    </div>
                    <br><br>
                    @endforeach
                @else
                    <p style="font-size: 200%;">No Exchange Requests at the moment or of this status.</p>
                @endif
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $exchanges->links() }}
            </div>
        </div>
    </div>
</div>
</div>
</div>

    <script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.onload = function () {
            const currentStatus = window.location.pathname.split('/').pop();

            if(currentStatus)
                document.getElementById('exchangeStatusFilter').value = currentStatus;
        };

        function filterExchanges(status) {
            window.location.href = '{{ route('exchangelist') }}/' + status;
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