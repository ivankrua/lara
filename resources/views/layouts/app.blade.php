<html>
<head>
    <title>App - @yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
</head>
<body>
<script>
    var appConfig = { 'token':'' };

    function validateUser()
    {
        if (appConfig.hasOwnProperty('token') && appConfig.token !== '')
        {
            $('#signout-btn').show();
        }
        else
        {
            $('#signout-btn').hide();
        }
    }

    function authUser(login, pass)
    {
        $('#signout-btn').hide();
    }
    appConfig.token = document.cookie
        .split('; ')
        .find(row => row.startsWith('signin_token'))
        .split('=')[1];
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Logo</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/manager">Admin page</a>
            </li>
        </ul>
        <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="signout-btn">Sign out</button>
    </div>
</nav>
<script>
    $('#signout-btn').click(()=>{
        $.ajax({
            type: "POST",
            url: 'api/logout',
            data:{},
            dataType: 'text',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer '+appConfig.token);
            },
            success: function(data) {
                appConfig.token = '';
                document.cookie = 'signin_token=';
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                appConfig.token = '';
                document.cookie = 'signin_token=';
                location.reload();
            }
        });
    });
</script>
<div class="container">
    @yield('content')
</div>
</body>
</html>
