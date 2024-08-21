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
                <a href="{{ route('userlist') }}"  class="selectedNav">
                    <i class="fas fa-address-book"></i>
                    <span class="nav-item"> User List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('productlist') }}">
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
        <h1>User List</h1>

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
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                <div class="input-group-append">
                    <select class="form-control ml-2" id="filterDropdown">
                        <option value="all">All</option>
                        <option value="BUYER">Buyers</option>
                        <option value="SELLER">Sellers</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <main class = "table">
        <section class="table_header">
            <h1>Users</h1>
        </section>
        <section class="table_body">
            <table>
            <!--table head-->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME/ORGANIZATION</th>
                        <th>EMAIL</th>
                        <th>TYPE</th>
                        <th>STATUS</th>
                        <th>WARNINGS</th>
                        <th>  </th>
                    </tr>
                </thead>
            <!--table body-->
                <tbody>
                @foreach ($userlist as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>
                            @if ($user->type === 'BUYER')
                                {{ $user->first_name }} {{ $user->last_name }}
                            @elseif ($user->type === 'SELLER')
                                {{ $user->org_name }}
                            @endif
                            </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->type }}</td>
                        <td>
                            @if ($user->is_disabled === 0)
                                Enabled
                            @elseif ($user->is_disabled === 1)
                                Disabled
                            @endif
                        </td>
                        <td>
                            @if (isset($warnCount[$user->user_id]))
                                {{ $warnCount[$user->user_id]->warning_count }}
                            @else
                                0
                            @endif
                        </td>
                        <td><a href="{{ url('/admin/userlist/userdetails/'.$user->user_id) }}" class="view"><b>View Details</b></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <div class="d-flex justify-content-between align-items-center">
        <div class="add-user text-left">
            <a href="{{ route('reports') }}" class="btn btn-primary">View Reports</a>
        </div>
        <div class="add-user text-right">
            <a href="{{ route('newseller') }}" class="btn btn-primary">Add New Seller</a>
        </div>
    </div>
</section>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const filterDropdown = document.getElementById('filterDropdown');
    const userRows = document.querySelectorAll('.table tbody tr');

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = filterDropdown.value;

        userRows.forEach(row => {
            const userType = row.querySelector('td:nth-child(4)').textContent;
            const nameOrg = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

            if (
                (selectedType === 'all' || userType === selectedType) &&
                (nameOrg.includes(searchTerm) || userType.includes(searchTerm))
            ) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterUsers);
    filterDropdown.addEventListener('change', filterUsers);

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