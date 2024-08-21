<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel = "stylesheet" href="{{ asset('css/styleHome.css') }}">
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
                <a href="{{ route('cart') }}" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" class="fas fa-shopping-cart"></a>
                <a href="{{ route('dashboard') }}" class="fas fa-user"></a>
                <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>

            </div>
    </header>
    
    <div class="container mt-4 checkoutForm">
        <!-- Display Buyer's Default Address -->
        <h2>Shipping Address</h2><br><br>
        <form method="POST" action="{{ route('final.checkout') }}" >
        @csrf
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" class="form-control" id="province" name="province" value="{{ $buyerAddress->province ?? '' }}">
                <span class="text-danger">@error('province') {{ $message }} @enderror</span>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" value="{{ $buyerAddress->city ?? '' }}">
                <span class="text-danger">@error('city') {{ $message }} @enderror</span>
            </div>
            <div class="form-group">
                <label for="street_add">Street Address</label>
                <input type="text" class="form-control" id="street_add" name="street_add" value="{{ $buyerAddress->street_add ?? '' }}">
                <span class="text-danger">@error('street_add') {{ $message }} @enderror</span>
            </div>
            <div class="form-group">
                <label for="postal">Postal Code</label>
                <input type="text" class="form-control" id="postal" name="postal" value="{{ $buyerAddress->postal ?? '' }}">
                <span class="text-danger">@error('postal') {{ $message }} @enderror</span>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $buyerAddress->phone ?? '' }}">
                <span class="text-danger">@error('phone') {{ $message }} @enderror</span>
            </div>

            <br>

            <!-- Display Cart Items Grouped by Seller -->
            @foreach ($cartItemsBySeller as $sellerId => $items)
                @php
                    $seller = $items[0]->product->user; // Seller information from the USER table
                    $sellerShippingFee = $fee->fee;
                    $totalQuantity = $items->sum('quantity');
                    $additionalFee = 0;

                    $additionalFee = (int)($totalQuantity / 5) * 30;
                    $sellerShippingFee += $additionalFee;
                @endphp
                <!-- Display Seller Information (Organization, Profile Picture, Chat Now button) -->
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        @if ($seller->prof_pic !== 'default_pics/default_prof_pic.jpg')
                            <img src="{{ asset('storage/custom_prof_pics/'.$seller->prof_pic) }}" alt="Profile Picture" style="width: 80px; height: 80px;">
                        @else
                            <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="Profile Picture" style="width: 80px; height: 80px;">
                        @endif
                        <h2 class="ml-3">{{ $seller->org_name }}</h2>
                    </div>
                    <a href="{{ $seller->chat_link }}" target="_blank" class="btn btn-dark">Chat Now</a>
                </div>
                <br><br><br>
                <!-- Display Cart Items for This Seller -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                @if ($item->prod_image !== 'default_pics/default_prod_pic.jpg')
                                    <img src="{{ asset('storage/custom_prod_pics/'.$item->prod_image) }}" alt="Product Image" style="width: 50px; height: 50px;">
                                @else
                                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 50px; height: 50px;">
                                @endif
                                    {{ $item->prod_name }}
                                </td>
                                <td>{{ $item->color ?? 'N/A' }}</td>
                                <td>{{ $item->size ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="shippingText">Shipping Fee: ₱{{ number_format($sellerShippingFee, 2) }}</p>
                <br>
            @endforeach

            <!-- Display Total and Checkout Button -->
            <div class="row justify-content-end">
                <div class="col-md-6 text-right">
                    <p class="shippingText">Total Shipping Fee: ₱{{ number_format($totalShippingFee, 2) }}</p>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-6 text-right">
                    <p class="shippingText">Total: ₱{{ number_format($total, 2) }}</p>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-danger">CHECK OUT</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const $statusFilter = $('#orderStatusFilter');
            const $orderItems = $('.order-item');

            $statusFilter.on('change', function () {
                const selectedStatus = $(this).val();

                $orderItems.hide();

                if (selectedStatus === '')
                    $orderItems.show();
                else
                {
                    $orderItems.each(function () {
                        const $orderItem = $(this);
                        const orderStatus = $orderItem.find('.order-status').text().trim();
                        if (orderStatus === selectedStatus)
                            $orderItem.show();
                    });
                }
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