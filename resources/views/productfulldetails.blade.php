<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
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
                @if($loggedIn == true)
                    <a href="{{ route('exchangelist') }}">Exchange Requests</a>
                @else
                    <a href="{{ route('login') }}">Exchange Requests</a>
                @endif
            </nav>

            <div class="icons">
                @if($loggedIn == true)
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
                @else
                    @php
                        $notifications = collect([]);
                    @endphp
                    <a href="{{ route('login') }}" class="fas fa-bell"></a>
                @endif
                <a href="#" class="fas fa-search" id="search-link"></a>
                    <div id="search-box">
                    <form id="search-form" action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" id="search-input" placeholder="Search products...">
                        <button type="submit">Search</button>
                    </form>
                    </div>
                @if($loggedIn == true)
                    <a href="{{ route('cart') }}" class="fas fa-shopping-cart"></a>
                    <a href="{{ route('dashboard') }}" class="fas fa-user"></a>
                    <a href="{{ route('logout') }}" class="fas fa-right-from-bracket"></a>
                @else
                    <a href="{{ route('login') }}" class="fas fa-shopping-cart"></a>
                    <a href="{{ route('login') }}" cl class="fas fa-user"></a>
                @endif
            </div>
    </header>


    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    
    <div class="product-details-container">
        <div class="product-images">
            @if ($product->image1 !== 'default_pics/default_prod_pic.jpg')
                <img id="main-image" src="{{ asset('storage/custom_prod_pics/'.$product->image1) }}" alt="Product Image" style="width: 400px; height: 400px;">
            @else
                <img id="main-image" src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 400px; height: 400px;">
            @endif
            <div class="thumbnail-images">
                @if ($product->image2 === null && $product->image3 === null && $product->image4 === null && $product->image5 === null)
                    <!-- don't display any images below the main image -->
                @else
                    <img class="thumbnail" src="{{ asset('storage/custom_prod_pics/'.$product->image1) }}" alt="Thumbnail Image 1" style="width: 100px; height: 100px;">
                @endif
                @if ($product->image2)
                    <img class="thumbnail" src="{{ asset('storage/custom_prod_pics/'.$product->image2) }}" alt="Thumbnail Image 2" style="width: 100px; height: 100px;">
                @endif
                @if ($product->image3)
                    <img class="thumbnail" src="{{ asset('storage/custom_prod_pics/'.$product->image3) }}" alt="Thumbnail Image 3" style="width: 100px; height: 100px;">
                @endif
                @if ($product->image4)
                    <img class="thumbnail" src="{{ asset('storage/custom_prod_pics/'.$product->image4) }}" alt="Thumbnail Image 4" style="width: 100px; height: 100px;">
                @endif
                @if ($product->image5)
                    <img class="thumbnail" src="{{ asset('storage/custom_prod_pics/'.$product->image5) }}" alt="Thumbnail Image 5" style="width: 100px; height: 100px;">
                @endif
            </div>
        </div>
        <div class="product-info">
            <form action="{{ route('add.cart') }}" class="formVariation" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
                <input type="hidden" name="prod_id" value="{{ $product->prod_id }}">
                <h1>{{ $product->prod_name }}</h1>
                <p class="product-price">${{ $product->price }}</p>
                <p id="stock-status">
                    @if ($product->quantity === 0)
                        Out of Stock
                    @else
                        Stock: {{ $product->quantity }}
                    @endif
                </p>
                @if ($variations->isNotEmpty())
                    <div class="variations">
                    @php
                        $hasColorVariations = $variations->contains(function ($variation) {
                            return !empty($variation->color);
                        });
                        $hasSizeVariations = $variations->contains(function ($variation) {
                            return !empty($variation->size);
                        });
                    @endphp

                    @if ($hasColorVariations && $hasSizeVariations)
                        <label for="variations">Variation:</label>
                    @elseif ($hasColorVariations)
                        <label for="variations">Color:</label>
                    @elseif ($hasSizeVariations)
                        <label for="variations">Size:</label>
                    @endif
                        <div id="variation-error" class="text-danger" style="display: none;">Please select a variation.</div>
                        <select id="variations" name="variations">
                            <option value="">Choose Variation</option>
                            @foreach ($variations as $variation)
                                @if (empty($variation->size))
                                    @if ($variation->quantity === 0)
                                        <option value="{{ $variation->color }}" data-quantity="{{ $variation->quantity }}" disabled>{{ $variation->color }}</option>
                                    @else
                                        <option value="{{ $variation->color }}" data-quantity="{{ $variation->quantity }}">{{ $variation->color }}</option>
                                    @endif
                                @elseif (empty($variation->color))
                                    @if ($variation->quantity === 0)
                                        <option value="{{ $variation->size }}" data-quantity="{{ $variation->quantity }}" disabled>{{ $variation->size }}</option>
                                    @else
                                        <option value="{{ $variation->size }}" data-quantity="{{ $variation->quantity }}">{{ $variation->size }}</option>
                                    @endif
                                @else
                                    @if ($variation->quantity === 0)
                                        <option value="{{ $variation->color }} - {{ $variation->size }}" data-quantity="{{ $variation->quantity }}" disabled>{{ $variation->color }} - {{ $variation->size }}</option>
                                    @else
                                        <option value="{{ $variation->color }} - {{ $variation->size }}" data-quantity="{{ $variation->quantity }}">{{ $variation->color }} - {{ $variation->size }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="quantity">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-input">
                        <button type="button" id="decrease-quantity">-</button>
                        <input type="text" id="quantity" name="quantity" value="1">
                        <button type="button" id="increase-quantity">+</button>
                        <div id="quantity-error" class="text-danger"></div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary addCart">Add to Cart</button>
            </form>
            <p class="product-description">{{ $product->description }}</p>
            <div class="seller-info">
                <div class="profile-pic">
                    @if ($userdata->prof_pic !== 'default_pics/default_prof_pic.jpg')
                        <img src="{{ asset('storage/custom_prof_pics/'.$userdata->prof_pic) }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                    @else
                        <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                    @endif
                </div>
                <p class="org-name">{{ $userdata->org_name }}</p> 
                <a href="{{ $userdata->chat_link }}" target="_blank" class="chat-button">Chat Now</a>
            </div>
        </div>
    </div>
    <div class="customer-reviews-header">
        <h2>Customer Reviews</h2>
        <br>
        <div class="average-rating">
            <p>Overall Rating: {{ number_format($averageRating, 1) }}</p>
            <!-- Add graphical representation of rating using stars -->
            <div id="rateYo"></div>
            @if($loggedIn == true)
                <a href="{{ url('/review/'.$product->prod_id) }}" class="btn btn-primary review-button">Review Product</a>
            @endif
        </div>
    </div>
    <div class="customer-reviews">
        @foreach ($reviews as $review)
            <div class="review">
                <div class="review-user">
                    <!-- Display user profile picture here -->
                </div>
                <div class="review-content">
                    @if ($review->user->prof_pic !== 'default_pics/default_prof_pic.jpg')
                        <img src="{{ asset('storage/custom_prof_pics/'.$review->user->prof_pic) }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                    @else
                        <img src="{{ asset('default_pics/default_prof_pic.jpg') }}" alt="User Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                    @endif
                    <p class="review-text">{{ $review->user->first_name }} {{ $review->user->last_name }}</p>
                    <!-- Display stars for rating -->
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rate)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="review-text">{{ $review->content }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="custom-pagination">
        {{ $reviews->links() }}
    </div>

        
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            const mainImage = document.getElementById('main-image');
            const thumbnails = document.querySelectorAll('.thumbnail');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', () => {
                    mainImage.src = thumbnail.src;
                });
            });

            const decreaseQuantityBtn = document.getElementById('decrease-quantity');
            const increaseQuantityBtn = document.getElementById('increase-quantity');
            const quantityInput = document.getElementById('quantity');
            const stockStatus = document.getElementById('stock-status');
            const quantityError = document.getElementById('quantity-error'); // New element for error message
            const overallQuantity = {{ $product->quantity }}; // Get the overall product quantity
            const variationsSelect = document.getElementById('variations');// Check if the variations select element exists
            const variationError = document.getElementById('variation-error');
            const addToCartButton = document.getElementById('add-to-cart-button'); // Check if the "Add to Cart" button exists

            if (variationsSelect) {
                // This product has variations, update stock status based on variations
                variationsSelect.addEventListener('change', updateStockStatus);

                // Function to update the stock status based on the selected variation
                function updateStockStatus() {
                    const selectedOption = variationsSelect.options[variationsSelect.selectedIndex];
                    const quantity = selectedOption.getAttribute('data-quantity');

                    if (selectedOption.value === '') {
                        // Default option selected, show overall product quantity
                        stockStatus.textContent = `Stock: ${overallQuantity}`;
                        quantityInput.max = overallQuantity; // Set max quantity to overall quantity
                    } else {
                        stockStatus.textContent = `Stock: ${quantity}`;
                        quantityInput.max = quantity; // Set max quantity to the selected variation's quantity
                    }

                    // Ensure that the input value does not exceed the maximum allowed quantity
                    const enteredQuantity = parseInt(quantityInput.value);
                    if (enteredQuantity > parseInt(quantityInput.max)) {
                        quantityInput.value = quantityInput.max;
                        quantityError.textContent = 'You have reached the maximum quantity available for this variation';
                    } else {
                        quantityError.textContent = ''; // Clear the error message
                    }
                }

                // Call the function on page load to initialize the stock status
                updateStockStatus();
            } else if (addToCartButton) {
                // Product has no variations, add event listener to "Add to Cart" button
                addToCartButton.addEventListener('click', () => {
                    const enteredQuantity = parseInt(quantityInput.value);
                    if (enteredQuantity > overallQuantity) {
                        quantityError.textContent = 'You have reached the maximum quantity available for this product';
                    } else {
                        quantityError.textContent = ''; // Clear the error message
                    }
                });
            }

            // For both products with and without variations
            decreaseQuantityBtn.addEventListener('click', () => {
                const currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;
                }
            });

            increaseQuantityBtn.addEventListener('click', () => {
                const currentQuantity = parseInt(quantityInput.value);
                if (variationsSelect) {
                    // Product with variations, get the selected variation's quantity
                    const selectedOption = variationsSelect.options[variationsSelect.selectedIndex];
                    const quantity = selectedOption.getAttribute('data-quantity');
                    if (currentQuantity < quantity) {
                        quantityInput.value = currentQuantity + 1;
                    }
                } else {
                    // Product with no variations, use overall product quantity
                    if (currentQuantity < overallQuantity) {
                        quantityInput.value = currentQuantity + 1;
                    }
                }
            });

            // Listen for changes in the quantity input field
            quantityInput.addEventListener('input', () => {
                const enteredQuantity = parseInt(quantityInput.value);
                if (variationsSelect) {
                    // Product with variations, get the selected variation's quantity
                    const selectedOption = variationsSelect.options[variationsSelect.selectedIndex];
                    const quantity = selectedOption.getAttribute('data-quantity');
                    if (enteredQuantity > quantity) {
                        quantityInput.value = quantity;
                        quantityError.textContent = 'You have reached the maximum quantity available for this variation';
                    } else {
                        quantityError.textContent = ''; // Clear the error message
                    }
                } else {
                    // Product with no variations, use overall product quantity
                    if (enteredQuantity > overallQuantity) {
                        quantityInput.value = overallQuantity;
                        quantityError.textContent = 'You have reached the maximum quantity available for this product';
                    } else {
                        quantityError.textContent = ''; // Clear the error message
                    }
                }
            });

            document.querySelector('.formVariation').addEventListener('submit', function(event) {
                if (variationsSelect.value === '') {
                    variationError.style.display = 'block';
                    event.preventDefault(); // Prevent form submission
                } else {
                    variationError.style.display = 'none';
                }
            });

            // Ensure quantity doesn't go below 1
            quantityInput.addEventListener('input', function () {
                let quantity = parseInt(quantityInput.value);
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                quantityInput.value = quantity;
            });

            function initializeRateYo(rating) {
            $("#rateYo").rateYo({
                rating: rating,
                starWidth: "20px",
                readOnly: true,
                ratedFill: "#FFD700" // Color for filled stars
            });
            }

            // Initialize RateYo with the average rating when the page has fully loaded
            $(document).ready(function() {
                initializeRateYo({{ $averageRating }});
            });

            // Rest of your script here...

            // When the variations select changes, reinitialize RateYo
            $('#variations').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var rating = parseFloat(selectedOption.data('rating'));

                // Reinitialize RateYo with the new rating
                initializeRateYo(rating);

                // Rest of your variations logic here...
            });

            //FOR SEARCH
            $(document).ready(function() {
                $('#filterCategoryDropdown, #filterOrgDropdown').on('change', function() {
                    $('#filterForm').submit();
                });
            });

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