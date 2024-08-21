<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <nav class="left-nav">
            <div class="logo">
                <img src="{{ asset('path_to_your_logo_image.png') }}" alt="University MarketShop">
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('adminprofile') }}">Profile</a></li>
                <li><a href="{{ route('userlist') }}">Users List</a></li>
                <li><a href="{{ route('productlist') }}">Products List</a></li>
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