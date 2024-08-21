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


    <link rel = "stylesheet" href="{{ asset('css/admin/styleAdUserMod.css') }}">
</head>


<body>

    <nav>
        <ul>
            <li>
                 <a href="{{ route('adminprofile') }}" class="logo">
                    <i class="fas fa-store"></i>
                    <img src="{{ asset('default_pics/logoS.png') }}" alt="">      
                 </a>
            </li>
            <li>
                <a href="{{ route('adminprofile') }}">
                    <i class="fas fa-user"></i>
                    <span class="nav-item"> Profile </span>
                </a>
            </li>
            <li>
                <a href="{{ route('userlist') }}"  >
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> User List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('productlist') }}" class="selectedNav">
                    <i class="fas fa-check-to-slot"></i>
                    <span class="nav-item"> Product List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('categorylist') }}">
                    <i class="fas fa-list"></i>
                    <span class="nav-item"> Category List </span>
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
        <h1>Products</h1>

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
            
    <div class = "container">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif(Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        <form action="{{ route('approveproduct') }}" method="POST">
        @csrf
        <div class = "details_container">
                <h2>Product Details</h2> 
            <br>
        <div class="flex-container">
            <div class="detailsBox">
                    <input type="hidden" name="prod_id" value="{{ $product->prod_id }}">

                            <div class="input-box">
                                <label for="prod_name">Product Name</label>
                                <input type="text" id="prod_name" name="prod_name" value="{{ $product->prod_name }}" readonly disabled>
                            </div>
                            <div class="input-box">
                                <label for="category">Category</label>
                                <select id="category" name="category" readonly disabled>
                                    <option value="{{ $product->category }}" selected>{{ $product->category }}</option>
                                </select>
                            </div>
                            <div class="description-box">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" readonly disabled>{{ $product->description }}</textarea>
                            </div>
                            <div class="input-box">
                                <label for="price">Price</label>
                                <input type="number" step="0.50" id="price" name="price" value="{{ $product->price }}" readonly disabled>
                            </div>
                            <div class="form-group" style="color: black;">
                            @if($variationexist)
                                <h5>Variations:</h5>
                                @foreach($variationlist as $variation)
                                    {{ $variation->color }} - {{ $variation->size }}<br>
                                @endforeach
                            @endif
                            </div> 
                            @if ($product->is_approved === 0 && $product->prod_status === 'DELETED')
                                <div class="form-group" style="color: black;">
                                    <label for="price">Reason for Rejection</label>
                                    <textarea class="form-control" id="reject_reason" name="reject_reason" rows="6" readonly disabled>{{ $product->reject_reason }}</textarea>
                                </div>
                            @endif

                        <div class="save">
                            @if ($product->is_approved === 0 && $product->prod_status === 'DELETED')
                                <a href="{{ route('rejectedlist') }}" class="btn btn-secondary" style="width: 45%">Back <br></a>
                            @elseif ($product->is_approved === 0 && $product->prod_status !== 'DELETED')
                            <div class="BtnContainer">
                                <a href="{{ route('productlist') }}" class="btn btn-secondary" style="width: 45%">Back <br></a> 
                                <div><br></div>
                                <div class="ButtonGroup">
                                    <button type="submit" class="btn btn-dark reject">Approve</button>
                                    <a href="{{ url('/admin/productlist/productdetails/rejectpage/'.$product->prod_id) }}" class="btn btn-danger reject">Reject</a>
                                </div>
                            </div>
                            @elseif ($product->is_approved === 1 && $product->prod_status === 'ENABLED')
                                <a href="{{ route('productlist') }}" class="btn btn-secondary" style="width: 30%">Back</a> <br>
                                <a href="{{ url('/admin/productlist/productdetails/disable/'.$product->prod_id) }}" class="btn btn-danger" style="width: 30%">Disable</a>
                            @elseif ($product->is_approved === 1 && $product->prod_status === 'DISABLED')
                                <a href="{{ route('productlist') }}" class="btn btn-secondary" style="width: 30%">Back</a> <br>
                                <a href="{{ url('/admin/productlist/productdetails/enable/'.$product->prod_id) }}" class="btn btn-danger" style="width: 30%">Enable</a>
                            @endif
                        </div>      
            </div>  
            <div class="ppBox">
                <label style="color: black;">Product Images</label><br>

                <div class="product-pic-preview">
                    @if ($product->image1 !== 'default_pics/default_prod_pic.jpg')
                        <img src="{{ asset('storage/custom_prod_pics/' . $product->image1) }}" alt="Product Picture" id="preview-image" class="img-fluid" style="width: 150px; height: 150px;">
                    @else
                        <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Default Product Picture" id="preview-image" class="img-fluid" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <br>
                <div class="product-pic-preview">
                    @if ($product->image2)
                        <img src="{{ asset('storage/custom_prod_pics/' . $product->image2) }}" alt="Product Picture" id="preview-image2" class="img-fluid" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <br>
                <div class="product-pic-preview">
                    @if ($product->image3)
                        <img src="{{ asset('storage/custom_prod_pics/' . $product->image3) }}" alt="Product Picture" id="preview-image3" class="img-fluid" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <br>
                <div class="product-pic-preview">
                    @if ($product->image4)
                        <img src="{{ asset('storage/custom_prod_pics/' . $product->image4) }}" alt="Product Picture" id="preview-image4" class="img-fluid" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <br>
                <div class="product-pic-preview">
                    @if ($product->image5)
                        <img src="{{ asset('storage/custom_prod_pics/' . $product->image5) }}" alt="Product Picture" id="preview-image5" class="img-fluid" style="width: 150px; height: 150px;">
                    @endif
                </div>   
                
            </div>
        </div> 
        </div>  
        </form>
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


