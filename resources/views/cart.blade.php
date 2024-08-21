<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel = "stylesheet" href="{{ asset('css/styleCart.css') }}">
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
                <a href="{{ route('cart') }}" style="font-size: 2rem; color: #4D0609; background: white; padding: .5rem .5rem; border-radius: .5rem;" class="fas fa-shopping-cart"></a>
                <a href="{{ route('dashboard') }}" class="fas fa-user"></a>
                <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>

            </div>
    </header>

<section>
<div class = "container">
	<div class = "account_container">
    <h2> CART </h2>
    @if (count($cartitems) > 0)
        <div class="orderBox">
            <h3>Your cart items</h3>
            @foreach ($cartitems as $cartitem)
                <div class="orderItem">
                        <div class="description">
                           <h5>{{ $cartitem->prod_name }}</h5>
                           @if ($cartitem->color)
                                <h5>{{ $cartitem->color }}</h5>
                            @endif
                            @if ($cartitem->size)
                                <p>{{ $cartitem->size }}</p>
                            @endif
                            <a href="{{ url('/cart/delete/'.$cartitem->cartitem_id) }}" class="btn btn-danger remove">Remove</a>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ url('/cart/update/'.$cartitem->cartitem_id) }}" method="POST">
                            @csrf
                             @method('PUT')
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="submit" name="quantity_change" value="decrement">-</button>
                                    </span>
                                    <input type="text" class="form-control text-center qnty" value="{{ $cartitem->quantity }}" name="quantity" readonly>
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit" name="quantity_change" value="increment">+</button>
                                    </span>
                                </div>
                            </form>
                            <p class="subtotal">Subtotal: ₱{{ number_format($cartitem->subtotal, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>Subtotal: ₱{{ number_format($cartitem->subtotal, 2) }}</p>
                            <!-- Error messages -->
                            @if ($cartitem->color || $cartitem->size)
                                <?php
                                    $variation = App\Models\Variation::where('prod_id', $cartitem->prod_id)->where('color', $cartitem->color)->where('size', $cartitem->size)->first();
                                ?>
                                @if (!$variation)
                                    <p class="text-danger">This variation no longer exists. Remove it before checking out.</p>
                                @elseif ($variation->quantity === 0)
                                    <p class="text-danger">This variation is out of stock. Remove it before checking out.</p>
                                @elseif ($cartitem->quantity > $variation->quantity)
                                    <p class="text-danger">There is not enough stock for this variation. Reduce the quantity or remove it before checking out.</p>
                                @endif
                            @else
                                <?php
                                $product = App\Models\Product::find($cartitem->prod_id);
                                ?>
                                @if ($product->prod_status === 'DELETED')
                                <p class="text-danger">This product no longer exists. Remove it before checking out.</p>
                                @elseif ($product->quantity === 0)
                                    <p class="text-danger">This product is out of stock. Remove it before checking out.</p>
                                @elseif ($cartitem->quantity > $product->quantity)
                                    <p class="text-danger">There is not enough stock for this product. Reduce the quantity or remove it before checking out.</p>
                                @endif
                            @endif
                        </div> 
                        <div class="image">
                            @if ($cartitem->prod_image !== 'default_pics/default_prod_pic.jpg')
                                    <img src="{{ asset('storage/custom_prod_pics/' . $cartitem->prod_image) }}" alt="{{ $cartitem->prod_name }}" class="img-fluid" style="width: 150px; height: 150px;">
                            @else
                                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="{{ $cartitem->prod_name }}" class="img-fluid" style="width: 150px; height: 150px;">
                            @endif
                        </div>
                </div>
                <hr>
            @endforeach
            <div class="total-info">
                <div class="desc text-right">
                    <p>Total:  ₱ {{ number_format($total, 2) }}</p>
                    <p>Shipping Fees are calculated upon Checkout</p>
                </div>
                <div class="desc">
                    <a href="{{ route('checkout') }}" class="btn btn-primary checkout-btn">Proceed to Checkout</a>
                </div>
            </div>
        </div>
        @else
            <p>Your cart is empty.</p>
        @endif
	</div>
</div>
</section>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript functions to increment and decrement quantity
        function updateCartItem(cartItemId) {
            var quantityInput = document.getElementById('quantity-' + cartItemId);
            var currentQuantity = parseInt(quantityInput.value);
            var form = document.getElementById('update-form-' + cartItemId);

            if (!isNaN(currentQuantity)) {
                form.submit();
            }
        }

        function incrementQuantity(cartItemId) {
            var quantityInput = document.getElementById('quantity-' + cartItemId);
            var currentQuantity = parseInt(quantityInput.value);
            if (!isNaN(currentQuantity)) {
                quantityInput.value = currentQuantity + 1;
                updateCartItem(cartItemId); // Submit the form
            }
        }

        function decrementQuantity(cartItemId) {
            var quantityInput = document.getElementById('quantity-' + cartItemId);
            var currentQuantity = parseInt(quantityInput.value);
            if (!isNaN(currentQuantity) && currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
                updateCartItem(cartItemId); // Submit the form
            }
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