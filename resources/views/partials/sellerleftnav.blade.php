<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <nav class="left-nav">
            <div class="logo">
                <img src="{{ asset('path_to_your_logo_image.png') }}" alt="University MarketShop">
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('sellerprofile') }}">My Account</a></li>
                <li><a href="{{ route('sellerproduct') }}">My Products</a></li>
                <li><a href="{{ route('sellerorder') }}">Incoming Orders</a></li>
                <li><a href="{{ route('sellerexchange') }}">Incoming Exchanges</a></li>
                <li><a href="{{ route('sales.report') }}">Sales Report</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <style>
        .wrapper {
            display: flex;
        }

        .left-nav {
            width: 250px;
            background-color: #f4f4f4;
            height: 100vh;
            padding-top: 20px;
        }

        .logo img {
            width: 100px;
            margin: 0 auto;
            display: block;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        .nav-links li {
            padding: 10px;
            text-align: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            flex-grow: 1;
        }
    </style>
</body>
</html>
