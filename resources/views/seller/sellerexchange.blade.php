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
    <link rel = "stylesheet" href="{{ asset('css/seller/sellerExchangeStyle.css') }}">
</head>
<body>
    <nav class="leftnav">
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
                <a href="{{ route('sellerorder') }}">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> Orders </span>
                </a>
            </li>
            <li>
                <a href="{{ route('sellerexchange') }}" class="selectedNav">
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
        <h1>Exhanges</h1>

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
    @endif
    <div class = "ordContainer">
        <div class = "account_container">
            <div class="orderBox">
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
                    @if ($exchanges->count() > 0)
                        @foreach ($exchanges as $exchange)
                        <div class="exchange-item">
                            <div class="d-flex justify-content-between align-items-center ">
                                <div class="exchange-status" style="display: none;">{{ $exchange->status }}</div>
                                <div>
                                    Buyer: {{ $exchange->buyer->first_name }} {{ $exchange->buyer->last_name }}
                                </div>
                                <div>
                                    Exchange ID: {{ $exchange->exchange_id }} | {{ $exchange->status }}
                                </div>
                            </div>
                            <hr>
                            <a href="{{ url('/seller/exchangedetails/'.$exchange->exchange_id) }}" class="detailsOrdExch">
                                <div class="exchItems">
                                    @foreach ($exchange->exchangeItems as $exchangeItem)
                                        <div class="exchange-item-row d-flex align-items-center">
                                            <div class="exchange-item-details text-left">
                                                @if ($exchangeItem->prod_image !== 'default_pics/default_prod_pic.jpg')
                                                    <img src="{{ asset('storage/custom_prod_pics/'.$exchangeItem->prod_image) }}" alt="Product Image" style="width: 3rem; height: 3rem;">
                                                @else
                                                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 3rem; height: 3rem;">
                                                @endif
                                                <div class="product-info">
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
                                            <div class="product-quantity text-right">
                                                Quantity: {{ $exchangeItem->quantity }}
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                @if ($exchange->status === 'PENDING')
                                    <div style="color: white;">
                                        <b>Click on me to review pending exchange request.</b>
                                    </div>
                                @elseif ($exchange->status === 'ON THE WAY TO SELLER')
                                    <div style="color: white;">
                                        <b>Parcel for return is on the way.</b>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <br>
                        @endforeach
                    @else
                        <p>No Orders at the moment.</p>
                    @endif
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $exchanges->links() }}
            </div>
        </div>
    </div>
</section>

<script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.onload = function () {
        const currentStatus = window.location.pathname.split('/').pop();

        if(currentStatus)
            document.getElementById('exchangeStatusFilter').value = currentStatus;
    };

    function filterExchanges(status) {
        window.location.href = '{{ route('sellerexchange') }}/' + status;
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
</body>
</html>