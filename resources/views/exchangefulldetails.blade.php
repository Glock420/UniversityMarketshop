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
        <h2>Exchange Details</h2>
        <br>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('exchangelist') }}" class="btn btn-danger buttones">Back</a>
            </div>
            <div>
                <p class="stats">Exchange ID: {{ $exchange->exchange_id }}  |  {{ $exchange->status }}</p>
            </div>
        </div>
        @if ($exchange->status !== 'PENDING' && $exchange->status !== 'CANCELED')
            <hr>
            <div class="address">
                <h4>Delivery Address to Seller</h4>
                {{ $seller->first_name }} {{ $seller->last_name }} <br>
                @if($address)
                    {{ $address->phone }} <br>
                    {{ $address->street_add }} <br>
                    {{ $address->city }}, {{ $address->province }}, {{ $address->postal }} <br>
                @else
                    <b>Chat with seller for their delivery address.</b> <br>
                @endif
                Date Requested: {{ $exchange->date }} <br>
            </div>
        @endif
        <hr>
        <div class="ordORG">
            <div class="order-status" style="display: none;">{{ $exchange->status }}</div>
            <div class="d-flex align-items-center">
                <h4 style="margin-bottom: 0%; margin-right: 1%;">{{ $seller->org_name }}</h4>
                <a href="{{ $seller->chat_link }}" target="_blank" class="btn btn-dark">Chat</a>
            </div>
        </div>
        <hr>
        <div class="exchange-items">
            @foreach ($exchangeitems as $exchangeitem)
                <a href="{{ url('/catalog/fulldetails/'.$exchangeitem->prod_id) }}" style="text-decoration: none; color: black;">
                <div class="exchange-item-row d-flex justify-content-between">
                    <div class="exchange-item-details">
                        @if ($exchangeitem->prod_image !== 'default_pics/default_prod_pic.jpg')
                            <img src="{{ asset('storage/custom_prod_pics/'.$exchangeitem->prod_image) }}" alt="Product Image" style="width: 100px; height: 100px;">
                        @else
                            <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 100px; height: 100px;">
                        @endif
                        <div class="product-info">
                            {{ $exchangeitem->prod_name }} <br>
                            @if ($exchangeitem->color && $exchangeitem->size)
                                {{ $exchangeitem->color }} - {{ $exchangeitem->size }}
                            @elseif ($exchangeitem->color)
                                {{ $exchangeitem->color }}
                            @elseif ($exchangeitem->size)
                                {{ $exchangeitem->size }}
                            @endif
                        </div>
                    </div>
                    <div class="product-quantity">
                        <p>Quantity: {{ $exchangeitem->quantity }}</p>
                    </div>
                </div>
                </a>
                <hr>
            @endforeach
        </div>
        <div class="reason">
            <b>Reason: </b>{{ $exchange->reason }}
        </div>
        <hr>
        @if ($exchange->details)
            <div class="details">
                <div><b>Additional Details:</b></div>
                <div>{{ $exchange->details }}</div>
            </div>
            <hr>
        @endif
        <div class="proof">
            <div>
                <b>Picture Proof:</b>
            </div>
            <div>
                <a href="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic1) }}" data-lightbox="proof" data-title="Picture Proof">
                    <img src="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic1) }}" alt="Proof" style="width: 100px; height: 100px;">
                </a>
                @if ($exchange->proof_pic2 !== 'default_pics/default_proof_pic.jpg')
                    <a href="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic2) }}" data-lightbox="proof" data-title="Picture Proof">
                        <img src="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic2) }}" alt="Proof" style="width: 100px; height: 100px;">
                    </a>
                @endif
                @if ($exchange->proof_pic3 !== 'default_pics/default_proof_pic.jpg')
                    <a href="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic3) }}" data-lightbox="proof" data-title="Picture Proof">
                        <img src="{{ asset('storage/custom_exch_pics/'.$exchange->proof_pic3) }}" alt="Proof" style="width: 100px; height: 100px;">
                    </a>
                @endif
            </div>
        </div>
        <hr>
        @if ($exchange->status === 'PENDING')
            <div class="row d-flex align-items-center">
                <div class="col-md-6 statsExch">
                    <b>Your request for exchange is under review.</b>
                </div>
                <div class="col-md-6 text-right statsExch">
                    <a href="{{ url('/exchange/exchangedetails/cancel/'.$exchange->exchange_id) }}" class="btn btn-danger" style="font-size:80%;">Cancel Request</a>
                </div>
            </div>
        @elseif ($exchange->status === 'ON THE WAY TO SELLER')
            <div style="font-size: 175%; color: red;">
                <b>Please pack item/s in original packaging and return to seller and wait for their confirmation.</b>
            </div>
        @elseif ($exchange->status === 'ON THE WAY TO BUYER')
            <div style="font-size: 175%;">
                <b>Your exchanged items are on the way.</b>
            </div>
        @elseif ($exchange->status === 'REJECTED')
            <div>
                <b>Your exchange request has been rejected due to insufficient proof/details.</b>
            </div>
        @elseif ($exchange->status === 'CANCELED')
            <div>
                <b>You have canceled your request.</b>
            </div>
        @endif
        <br><br>
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