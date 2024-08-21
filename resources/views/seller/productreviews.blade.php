<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link rel = "stylesheet" href="{{ asset('css/seller/sellerReviewsStyle.css') }}">
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

    <section class="ordExch" style="margin-left: 10%">
        @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif
        <br>
        <a href="{{ route('sellerproduct') }}" class="btn btn-danger" id="back">Back</a>
        <div class="customer-reviews-header">
            <br>
            <h2>{{ $product->prod_name }} Reviews/Ratings</h2>
            <div class="d-flex align-items-center">
                Overall Rating: {{ number_format($averageRating, 1) }}
                <!-- Add graphical representation of rating using stars -->
                <div id="rateYo"></div>
            </div>
        </div>
        <br><br>
        <div>
            @foreach ($reviews as $review)
                <div>
                    <div class="d-flex align-items-center">
                        <div>
                            @if ($review->user->prof_pic !== 'default_pics/default_prof_pic.jpg')
                                <img src="{{ asset('storage/custom_prof_pics/'.$review->user->prof_pic) }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                            @else
                                <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                            @endif
                        </div>
                        <div style="margin-left: 1%">
                            {{ $review->user->first_name }} {{ $review->user->last_name }}
                        </div>
                        <div class="col-md-9 text-right"><a href="{{ url('/seller/orderdetails/reportuser/'.$review->rev_id.'/review') }}" class="btn btn-danger">Report</a></div>
                    </div>
                    <div>
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rate)
                                    <i class="fas fa-star filled-star"></i>
                                @else
                                    <i class="far fa-star empty-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p>{{ $review->content }}</p>
                    </div>
                </div>
                <hr>
            @endforeach
            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        function initializeRateYo(rating) {
            $("#rateYo").rateYo({
                rating: rating,
                starWidth: "20px",
                readOnly: true,
                ratedFill: "#FFD700"
            });
        }

        $(document).ready(function() {
            initializeRateYo({{ $averageRating }});
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
    </script>
    <style>
        .rating-stars {
            display: flex;
            align-items: center;
        }

        .rating-stars i {
            font-size: 20px; /* Set the font size for stars */
            margin: 0; /* Remove default margin */
            width: 20px; /* Set a custom width for stars */
            vertical-align: middle; /* Align stars vertically */
        }

        .filled-star {
            color: #FFD700; /* Color for filled stars (yellow) */
        }

        .empty-star {
            color: transparent; /* Make empty stars transparent */
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #FFD700; /* Set the yellow outline for empty stars */
        }
    </style>
</body>
</html>