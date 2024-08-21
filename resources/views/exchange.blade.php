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
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
<div class = "ordContainer">
    <div class="container mt-4">
        <h2>Request for an Exchange</h2>
        <br>
        <a href="{{ url('/dashboard/orderdetails/'.$order->order_id) }}" class="btn btn-danger buttones">Back</a>
        <hr>
        <form method="POST" action="{{ route('exchange') }}" enctype="multipart/form-data">
        @csrf
            <input type="hidden" name="order_id" id="order_id" value="{{ $order->order_id }}">
            <input type="hidden" name="buyer_id" id="buyer_id" value="{{ $order->buyer_id }}">
            <input type="hidden" name="seller_id" id="seller_id" value="{{ $order->seller_id }}">
            <input type="hidden" name="phone" id="phone" value="{{ $order->phone }}">
            <input type="hidden" name="province" id="province" value="{{ $order->province }}">
            <input type="hidden" name="city" id="city" value="{{ $order->city }}">
            <input type="hidden" name="street_add" id="street_add" value="{{ $order->street_add }}">
            <input type="hidden" name="postal" id="postal" value="{{ $order->postal }}">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if ($seller->prof_pic !== 'default_pics/default_prof_pic.jpg')
                        <img src="{{ asset('storage/custom_prof_pics/'.$seller->prof_pic) }}" alt="Profile Picture" style="width: 60px; height: 60px;">
                    @else
                        <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="Profile Picture" style="width: 60px; height: 60px;">
                    @endif
                    <h4 class="ml-3">{{ $seller->org_name }}</h4>
                </div>
                <a href="#" class="btn btn-dark buttones">Chat Now</a>
            </div>
            <hr>
            <div>
                @foreach ($orderitems as $orderitem)
                    <div class="row d-flex align-items-center">
                        <div class="order-item-details col-md-6">
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
                        <div class="product-quantity col-md-6 text-right">
                            <input type="number" name="quantities[{{ $orderitem->orderitem_id }}]" id="quantity_{{ $orderitem->orderitem_id }}" min="1" max="{{ $orderitem->quantity }}" value="1">
                            <input type="checkbox" name="selected_items[]" value="{{ $orderitem->orderitem_id }}">
                        </div>
                        <span style="font-size: 150%;" class="text-danger">@error('quantities.' . $orderitem->orderitem_id) {{ $message }} @enderror</span>
                    </div>
                    <hr>
                @endforeach
            <span class="text-danger">@error('selected_items') {{$message}} @enderror</span>
            </div>
            <br>
            <div class="row d-flex align-items-center notes">
                Note: Depending on the reason, provided proof and further details of the problem, seller may or may not approve the request for exchange. To send additional proof, you may use the Chat Now feature to directly have a conversation with the seller.
            </div>
            <br>
            <div class="row d-flex align-items-center rason">
                <div>
                    Reason:
                </div>
                <div class="col-md-6">
                    <select id="reason" name="reason" class="form-control" required>
                        <option disabled selected>Choose Reason</option>
                        <option>Damaged Product/s (e.g. dented, scratched, shattered)</option>
                        <option>Faulty/Defective Product/s (e.g. malfunction, does not work as intended)</option>
                        <option>Wrong Product/Product Variation</option>
                        <option>Exchange Product Size</option>
                    </select>
                </div>
            </div>
            <br><br><br>
            <div class="row d-flex align-items-center proofPics">
                <div>
                    <label for="proof_pic1">Proof 1</label>
                    <div class="product-pic-preview">
                        <img src="{{ asset('default_pics/default_proof_pic.jpg') }}" alt="Proof" id="proof1" class="img-fluid" style="width: 150px; height: 150px;">
                        <span class="text-danger">@error('proof_pic1') {{$message}} @enderror</span>
                    </div>
                    <input type="file" id="proof_pic1" name="proof_pic1" accept="image/*" required>

                </div>
                <div>
                    <label for="proof_pic2">Proof 2</label>
                    <div class="product-pic-preview">
                        <img src="{{ asset('default_pics/default_proof_pic.jpg') }}" alt="Proof" id="proof2" class="img-fluid" style="width: 150px; height: 150px;">
                        <span class="text-danger">@error('proof_pic2') {{$message}} @enderror</span>
                    </div>
                    <input type="file" id="proof_pic2" name="proof_pic2" accept="image/*">

                </div>
                <div>
                    <label for="proof_pic3">Proof 3</label>
                    <div class="product-pic-preview">
                        <img src="{{ asset('default_pics/default_proof_pic.jpg') }}" alt="Proof" id="proof3" class="img-fluid" style="width: 150px; height: 150px;">
                        <span class="text-danger">@error('proof_pic3') {{$message}} @enderror</span>
                    </div>
                    <input type="file" id="proof_pic3" name="proof_pic3" accept="image/*">

                </div>
            </div>
            <br><br><br>
            <div class="row d-flex align-items-center addReasons" >
                <label for="details">Additional Details:</label>
                <textarea class="form-control" id="details" name="details" rows="6"></textarea>
            </div>
            <br>
            <div class="row justify-content-end">
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-danger buttones">Request Exchange</button>
                </div>
            </div>
            <br>
        </form>
    </div>
</div>
</div>

    <script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const proofPicInputs = [
            document.getElementById('proof_pic1'),
            document.getElementById('proof_pic2'),
            document.getElementById('proof_pic3')
        ];

        const proofPreviewImages = [
            document.getElementById('proof1'),
            document.getElementById('proof2'),
            document.getElementById('proof3')
        ];

        for (let i = 0; i < proofPicInputs.length; i++) {
            const input = proofPicInputs[i];
            const preview = proofPreviewImages[i];

            input.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            window.addEventListener('beforeunload', function () {
                input.value = '';
                preview.src = "{{ asset('default_pics/default_proof_pic.jpg') }}";
            });
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