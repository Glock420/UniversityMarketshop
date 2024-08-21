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
            
    <div class = "container">
        @if(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif
        <form action="{{ route('save.seller.product') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        <div class = "details_container">
            <h2>Add New Product</h2> 
            <br>
            <div class="flex-container">
                <div class="detailsBox">
                    <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">

                        <div class="input-box">
                            <label for="prod_name">Product Name</label>
                            <input type="text" id="prod_name" name="prod_name" required>
                            <span class="text-danger">@error('prod_name') {{ $message }} @enderror</span>
                        </div>
                        <div class="input-box">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <option disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                    <option>{{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">@error('category') {{ $message }} @enderror</span>
                        </div>
                        <div class="description-box">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4" required></textarea>
                                <span class="text-danger">@error('description') {{ $message }} @enderror</span>
                        </div>
                        <div class="input-box">
                                <label for="price">Price</label>
                                <input type="number" step="0.50" class="form-control" id="price" name="price" required>
                                <span class="text-danger">@error('price') {{ $message }} @enderror</span>
                        </div>
                        <div class="input-box">
                                <button type="button" class="btn btn-secondary customBtn" id="add-color">Add Color</button>
                        </div>
                        <div id="colors-container" style="color: black;">
                                <!-- color fields will be added here dynamically -->
                        </div>
                        <div class="input-box">
                                <button type="button" class="btn btn-secondary customBtn" id="add-size">Add Size</button>
                        </div>
                        <div id="sizes-container" style="color: black;">
                                <!-- size fields will be added here dynamically -->
                        </div>

                        <br>
                        <div class="row save">
                            <div class="col-md-6">
                                <a href="{{ route('sellerproduct') }}" class="btn btn-dark" style="width: 50%">Back</a><br>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-danger" style="width: 50%">Done</button>
                            </div>
                        </div>         
            </div>  <!--  details container   -->
            <div class="ppBox">
                <label style="font-weight: bold">Product Images</label><br>
                <input type="file" id="image1" name="image1">

                <div class="product-pic-preview">
                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Picture" id="preview-image" class="img-fluid" style="width: 150px; height: 150px;">
                    <span class="text-danger">@error('image1') {{$message}} @enderror</span>
                </div>
                <br>
                <label for="image2">2nd Image (optional):</label>
                <input type="file" id="image2" name="image2">
                <div class="product-pic-preview">
                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Picture" id="preview-image2" class="img-fluid" style="width: 150px; height: 150px;">
                    <span class="text-danger">@error('image2') {{$message}} @enderror</span>
                </div>
                <br>
                <label for="image3">3rd Image (optional):</label>
                <input type="file" id="image3" name="image3">
                <div class="product-pic-preview">
                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Picture" id="preview-image3" class="img-fluid" style="width: 150px; height: 150px;">
                    <span class="text-danger">@error('image3') {{$message}} @enderror</span>
                </div>
                <br>
                <label for="image4">4th Image (optional):</label>
                <input type="file" id="image4" name="image4">
                <div class="product-pic-preview">
                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Picture" id="preview-image4" class="img-fluid" style="width: 150px; height: 150px;">
                    <span class="text-danger">@error('image4') {{$message}} @enderror</span>
                </div>
                <br>
                <label for="image5">5th Image (optional):</label>
                <input type="file" id="image5" name="image5">
                <div class="product-pic-preview">
                    <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Picture" id="preview-image5" class="img-fluid" style="width: 150px; height: 150px;">
                    <span class="text-danger">@error('image5') {{$message}} @enderror</span>
                </div>
                
            </div>
        </div> 
        </div>  
        </form>
    </div>
</section> 


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const prodPicInputs = [
        document.getElementById('image1'),
        document.getElementById('image2'),
        document.getElementById('image3'),
        document.getElementById('image4'),
        document.getElementById('image5')
    ];

    const previewImages = [
        document.getElementById('preview-image'),
        document.getElementById('preview-image2'),
        document.getElementById('preview-image3'),
        document.getElementById('preview-image4'),
        document.getElementById('preview-image5')
    ];

    for(let i = 0; i < prodPicInputs.length; i++)
    {
        const input = prodPicInputs[i];
        const preview = previewImages[i];

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

        window.addEventListener('beforeunload', function () {       //reset the picture and input on page refresh
            input.value = '';
            preview.src = "{{ asset('default_pics/default_prod_pic.jpg') }}";
        });
    }

    //for adding color fields dynamically
    const colorsContainer = document.getElementById('colors-container');
    const addColorButton = document.getElementById('add-color');

    addColorButton.addEventListener('click', function () {
        createField('Color  ', 'color[]');
        updateQuantityField();
    });

    //for adding size fields dynamically
    const sizesContainer = document.getElementById('sizes-container');
    const addSizeButton = document.getElementById('add-size');

    addSizeButton.addEventListener('click', function () {
        createField('Size  ', 'size[]');
        updateQuantityField();
    });

    function createField(labelText, inputName) {
        const fieldContainer = document.createElement('div');
        fieldContainer.classList.add('form-group', 'd-flex', 'align-items-center');

        const label = document.createElement('label');
        label.textContent = labelText;

        const input = document.createElement('input');
        input.type = 'text';
        input.name = inputName;
        input.classList.add('form-control');

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'ml-2');
        removeButton.innerHTML = '&times;';     //use "times" symbol (X) as remove button

        removeButton.addEventListener('click', function () {
            fieldContainer.remove();
            updateQuantityField();
        });

        fieldContainer.appendChild(label);
        fieldContainer.appendChild(input);
        fieldContainer.appendChild(removeButton);

        if (inputName === 'color[]') {
            colorsContainer.appendChild(fieldContainer);
        } else if (inputName === 'size[]') {
            sizesContainer.appendChild(fieldContainer);
        }
    }

    function updateQuantityField() {
        const quantityContainer = document.getElementById('quantity-container');
        const colorFields = colorsContainer.querySelectorAll('[name="color[]"]');
        const sizeFields = sizesContainer.querySelectorAll('[name="size[]"]');
        const quantityInput = document.getElementById('quantity');

        if (colorFields.length > 0 || sizeFields.length > 0) {
            quantityInput.disabled = true;
        } else {
            quantityInput.disabled = false;
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
</script>


</body>
</html>