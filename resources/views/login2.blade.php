<!DOCTYPE html>
<html>
<head>
    <title>Seller's Portal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">SELLER HUB LOGIN</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login.seller') }}">
                            @csrf
                            @if(Session::has('fail'))
                                <div class = "alert alert-danger">{{Session::get('fail')}}</div>
                            @endif
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">LOGIN</button>
                            <div class="mt-2">
                                <a href="#">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>