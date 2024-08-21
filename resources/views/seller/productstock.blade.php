<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
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
                <a href="{{ route('sellerproduct') }}" class="selectedNav">
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
        <h1>My Products</h1>

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
<section class="details">
            

<div class="product-stock editStk">
        <h2>Add {{ $product->prod_name }} Stock</h2>

    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
    @if(!$variationexist)
        <form action="{{ route('add.stock') }}" method="POST">
            @csrf
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group stockOutput">
                        <h5><br>Current Stock: {{ $product->quantity }}</h5>
                        <br>
                        <input type="hidden" name="prod_id" value="{{ $product->prod_id }}">
                        <label for="quantity">Add Stock:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity">
                        <button type="submit" class="btn btn-success">Add</button>
                        <span class="text-danger">@error('quantity') {{ $message }} @enderror</span>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group">
                    <h5><br>Current Variation Stock:</h5>
                    @foreach($variationlist as $variation)
                    <span class="stockOutput">
                        {{ $variation->color }} {{ $variation->size }}: {{ $variation->quantity }} <br>
                    </span>
                    @endforeach
                    <br><br>
                    @foreach($variationlist as $variation)
                        <form action="{{ route('add.stock2') }}" method="POST">
                            @csrf
                            <input type="hidden" name="prod_id" value="{{ $product->prod_id }}">
                            <input type="hidden" name="variation_id[]" value="{{ $variation->variation_id }}">
                            <label for="quantity">Add {{ $variation->color }} {{ $variation->size }} Stock:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity[]" value="0">
                            <button type="submit" class="btn btn-danger">Add</button>
                            <span class="text-danger">@error('quantity') {{ $message }} @enderror</span> <br><br>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    
    <br>
    <div class="table-responsive stockLog">
        <h3>Product Stock Log:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Audit Log</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditlist as $audit)
                <tr>
                    <td>{{ $audit->audit_id }}</td>
                    <td>{{ $audit->audit_trail }}</td>
                    <td>{{ $audit->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</section> 

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
