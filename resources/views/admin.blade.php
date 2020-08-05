@extends('layouts.app')

@section('title', 'Admin')

@section('content')
    @parent

    <div id="signin_form">
        <form>
            <div class="form-group" id="user-name-group">
                <label for="userName">User name</label>
                <input type="email" class="form-control" id="userName" aria-describedby="emailHelp" placeholder="Your name">
            </div>
            <div class="form-group">
                <label for="userEmail">Email address</label>
                <input type="email" class="form-control" id="userEmail" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="userPassword">Password</label>
                <input type="password" class="form-control" id="userPassword" placeholder="Password">
            </div>
            <div class="form-group" id="register-group">
                <label for="userPasswordConfirmation">Password confirmation</label>
                <input type="password" class="form-control" id="userPasswordConfirmation" placeholder="Retype password">
            </div>
            <button type="button" class="btn btn-primary" id="signin-btn">Sign in</button>
            <button type="button" class="btn btn-secondary" id="register-btn">Register</button>
            <button type="button" class="btn btn-primary" id="register-primary-btn">Register</button>
        </form>
    </div>
    <table class="table table-bordered" id="data_table">
        <thead>
        <tr>
            <th scope="col">Provider</th>
            <th scope="col">Brand</th>
            <th scope="col">Location</th>
            <th scope="col">CPU</th>
            <th scope="col">Drive</th>
            <th scope="col">Price</th>
        </tr>
        </thead>
        <tbody id="pricelist">

        </tbody>
    </table>

    <script>
        function readData()
        {
            $.ajax({
                url: 'api/admin_pricelist',
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer '+appConfig.token);
                },
                success: function(data) {
                    $('#signin_form').hide();
                    if ('data' in data)
                    {
                        let serverList = data['data'];
                        let tableData = '';
                        serverList.forEach((item)=>{
                            tableData+='<tr><td>'+item.provider
                                +'</td><td>'+item.brand
                                +'</td><td>'+item.location
                                +'</td><td>'+item.cpu
                                +'</td><td>'+item.drive
                                +'</td><td>$'+Math.round(item.price * 100) / 100+'</td></tr>'
                        })
                        $('#pricelist').html(tableData);
                    }
                    else
                    {
                        $('#pricelist').html('<tr><td colspan="6">Oops! Something happend: '+JSON.stringify(data)+'</td></tr>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#data_table').hide();
                    $('#signin_form').show();
                }
            });
        }

        validateUser();
        $('#register-group').hide();
        $('#register-primary-btn').hide();
        $('#user-name-group').hide();
        $('#signin-btn').click(()=>{
            $.ajax({
                type: "POST",
                url: 'api/login',
                data:
                    {
                        email:$('#userEmail').val(),
                        password:$('#userPassword').val(),
                    },
                dataType: 'text',
                success: function(data) {
                    let response = JSON.parse(data);
                    $('#signin_form').hide();
                    if (response !== null && response.hasOwnProperty('token'))
                    {
                        appConfig.token = response.token;
                        document.cookie = 'signin_token='+response.token;
                        $('#data_table').show();
                        $('#signin_form').hide();
                        readData();
                    }
                    else
                    {
                        $('#data_table').hide();
                        $('#signin_form').show();
                        $('#register-group').hide();
                        $('#register-primary-btn').hide();
                        $('#user-name-group').hide();
                        console.log(data);
                        alert('Something went wrong!')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#data_table').hide();
                    $('#signin_form').show();
                    $('#register-group').hide();
                    $('#register-primary-btn').hide();
                    $('#user-name-group').hide();
                    alert('error: Something went wrong!')
                }
            });
        });
        $('#register-btn').click(()=>{
            $('#register-group').show();
            $('#register-primary-btn').show();
            $('#user-name-group').show();
            $('#signin-btn').hide();
            $('#register-btn').hide();
        });
        $('#register-primary-btn').click(()=>{
            $.ajax({
                type: "POST",
                url: 'api/register',
                data:
                {
                    name:$('#userName').val(),
                    email:$('#userEmail').val(),
                    password:$('#userPassword').val(),
                    password_confirmation:$('#userPasswordConfirmation').val()
                },
                dataType: 'text',
                success: function(data) {
                    $('#signin_form').hide();
                    let response = JSON.parse(data);
                    if (response !== null && response.hasOwnProperty('token'))
                    {
                        appConfig.token = response.token;
                        document.cookie = 'signin_token='+response.token;
                        $('#data_table').show();
                        $('#signin_form').hide();
                        readData();
                    }
                    else
                    {
                        $('#data_table').hide();
                        $('#signin_form').show();
                        $('#register-group').hide();
                        $('#register-primary-btn').hide();
                        $('#user-name-group').hide();
                        console.log(data);
                        alert('Something went wrong!')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#data_table').hide();
                    $('#signin_form').show();
                    $('#register-group').hide();
                    $('#register-primary-btn').hide();
                    $('#user-name-group').hide();
                    alert('Something went wrong!')
                }
            });
        });
        readData();
    </script>
@endsection
