<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

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
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
<div class = "ordContainer">

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('sellerexchange') }}" class="btn btn-danger" id="back">Back</a>
            </div>
            <div>
                <p>Exchange ID: {{ $exchange->exchange_id }}  |  {{ $exchange->status }}</p>
            </div>
        </div>
        <hr>
        <div class="address">
            <h4>Delivery Address to Buyer</h4>
            {{ $buyer->first_name }} {{ $buyer->last_name }} <br>
            {{ $exchange->phone }} <br>
            {{ $exchange->street_add }} <br>
            {{ $exchange->city }}, {{ $exchange->province }}, {{ $exchange->postal }} <br>
            Date Requested: {{ $exchange->date }} <br>
        </div>
        <hr>
        <div class="d-flex align-items-center">
            <div class="col-md-6"><h5><b>Buyer: </b>{{ $buyer->first_name }} {{ $buyer->last_name }}</h5></div>
            <div class="col-md-6 text-right"><a href="{{ url('/seller/orderdetails/reportuser/'.$exchange->exchange_id.'/exchange') }}" class="btn btn-danger">Report</a></div>
        </div>
        <hr>
        <div class="exchange-items">
            @foreach ($exchangeitems as $exchangeitem)
                <a href="{{ url('/seller/product/editproduct/'.$exchangeitem->prod_id) }}" style="text-decoration: none; color: black;">
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
            <!-- <div class="details"> -->
            <div>
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
                <div class="col-md-8">
                    <b>Please check and review the exchange request as soon as possible.</b>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ url('/seller/exchangedetails/approve/'.$exchange->exchange_id) }}" class="btn btn-secondary">Approve Request</a> | <a href="{{ url('/seller/exchangedetails/reject/'.$exchange->exchange_id) }}" class="btn btn-danger">Reject Request</a>
                </div>
            </div>
            <br><br>
        @elseif ($exchange->status === 'ON THE WAY TO SELLER')
            @if ($exchange->reason === 'Exchange Product Size')
            <div>                                       <!-- This part be under construction -->
                <b>Items to Send to Buyer:</b>
                <table>
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>VARIATION</th>
                            <th>QUANTITY</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returnItems as $returnItem)
                            <tr>
                                <td>{{ $returnItem->prod_name }}</td>
                                <td>{{ $returnItem->color }} {{ $returnItem->size }}</td>
                                <td>{{ $returnItem->quantity }}</td>
                                <td><a href="{{ url('/seller/exchangedetails/removereturnitem/'.$returnItem->returnitem_id) }}" class="btn btn-warning">Remove</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br>
                <b>Add Items to Send for Exchange:</b>
                <form method="POST" action="{{ route('add.return.item') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="exchange_id" id="exchange_id" value="{{ $exchange->exchange_id }}">
                    <label>Product:</label>
                    <select id="product" name="product" class="form-control" required>
                        <option value="" disabled selected>Choose Product</option>
                        @foreach ($sellerProducts as $product)
                            <option value="{{ $product->prod_id }}">{{ $product->prod_name }}</option>
                        @endforeach
                    </select>
                    <label>Size:</label>
                    <select id="variation" name="variation" class="form-control" required>
                        @if ($sellerProducts->isEmpty())
                            <option disabled selected>Choose Size</option>
                            @foreach ($variationOptions as $variation)
                                <option value="{{ $variation->variation_id }}">{{ $variation->size }}</option>
                            @endforeach
                        @else
                            <option disabled selected>Select a Product First</option>
                        @endif
                    </select>
                    <label>Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" step="1" required>
                    <button type="submit" class="btn btn-danger">Add</button>
                </form>
                <span class="text-danger">@error('product') {{ $message }} @enderror</span>
                <span class="text-danger">@error('variation') {{ $message }} @enderror</span>
                <span class="text-danger">@error('quantity') {{ $message }} @enderror</span>
            </div>
            <hr>
            @endif
            <div class="row d-flex align-items-center">
                <div class="col-md-8">
                    <b>Parcel for return is on the way.</b>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ url('/seller/exchangedetails/receive/'.$exchange->exchange_id) }}" class="btn btn-secondary">Parcel Received</a>
                </div>
            </div>
            <br><br>
        @elseif ($exchange->status === 'ON THE WAY TO BUYER')
            <div>
                <b>Please pack the exchanged items and ship to buyer.</b>
            </div>
            <br><br>
        @elseif ($exchange->status === 'CANCELED')
            <div>
                <b>Buyer has canceled the exchange request.</b>
            </div>
            <br><br>
        @endif
    </div>
</div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/8f9ed07ddd.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    function resetProductDropdown() {
        $('#product').val('');
        $('#variation').empty().append($('<option>', {
            value: '',
            text: 'Choose Size'
        }));
    }

    $(document).ready(function () {
        resetProductDropdown();

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

        $('#btn-danger').on('click', function() {
            resetProductDropdown();
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

    $(document).ready(function () {
        $('#product').change(function () {
            var productId = $(this).val();
            var exchangeId = '{{ $exchange->exchange_id }}';

            $.ajax({
                url: '/seller/exchangedetails/getvariations/' + productId,
                type: 'GET',
                data: { exchange_id: exchangeId, },
                success: function (data) {
                    $('#variation').empty();

                    $('#variation').append($('<option>', {
                        value: '',
                        text: 'Choose Size'
                    }));

                    $.each(data, function (index, variation) {
                        $('#variation').append($('<option>', {
                            value: variation.variation_id,
                            text: variation.size
                        }));
                    });
                },
                error: function () {
                    // Handle error
                }
            });
        });
    });
</script>

</body>
</html>