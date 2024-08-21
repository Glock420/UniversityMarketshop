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
                <a href="{{ route('userlist') }}">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> User List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('productlist') }}"  class="selectedNav">
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
        <h1>Product List</h1>

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

<section class="secBody">

    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif

    <div class="row mt-3 mb-3 justify-content-end">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                <div class="input-group-append">
                    <select class="form-control ml-2" id="filterCategoryDropdown">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->cat_name }}">{{ $category->cat_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group-append">
                    <select class="form-control ml-2" id="filterOrgDropdown">
                        <option value="all">All Organizations</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization }}">{{ $organization }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <main class="table">
        <section class="table_header">
            <h1>Rejected Products</h1>
        </section>
        <section class="table_body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rejectedlist as $rejected)
                    <tr>
                        <td>{{ $rejected->prod_id }}</td>
                        <td>{{ $rejected->prod_name }}</td>
                        <td>{{ $rejected->category }}</td>
                        <td>{{ $rejected->price }}</td>
                        <td>{{ $rejected->user->org_name }}</td>
                        <td>{{ $rejected->prod_status }}</td>
                        <td><a href="{{ url('/admin/productlist/productdetails/'.$rejected->prod_id) }}"><b>View Details</b></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
</main>

    <br>

    <div>
        <a href="{{ route('productlist') }}" class="btn btn-light">Back</a>
    </div>
</div>
</section>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const filterCategoryDropdown = document.getElementById('filterCategoryDropdown');
    const filterOrgDropdown = document.getElementById('filterOrgDropdown');
    const productRows = document.querySelectorAll('.table tbody tr');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = filterCategoryDropdown.value.toLowerCase();
        const selectedOrg = filterOrgDropdown.value.toLowerCase();

        productRows.forEach(row => {
            const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const org = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            const isCategoryMatch = selectedCategory === 'all' || category === selectedCategory;
            const isOrgMatch = selectedOrg === 'all' || org === selectedOrg;
            const isSearchMatch = name.includes(searchTerm);

            if (isCategoryMatch && isOrgMatch && isSearchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProducts);
    filterCategoryDropdown.addEventListener('change', filterProducts);
    filterOrgDropdown.addEventListener('change', filterProducts);

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