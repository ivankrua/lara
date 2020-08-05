@extends('layouts.app')

@section('title', 'Admin')

@section('content')
    @parent

    <div id="signin_form">
        <form>
            <div class="form-group" id="user-name-group">
                <label for="userName">User name</label>
                <input type="email" class="form-control" id="userName" aria-describedby="emailHelp"
                       placeholder="Your name">
            </div>
            <div class="form-group">
                <label for="userEmail">Email address</label>
                <input type="email" class="form-control" id="userEmail" aria-describedby="emailHelp"
                       placeholder="Enter email">
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
    <div id="restricted-zone">
        <form class="form-inline">
            <input type="file" id="newList">
            <input type="button" value="Submit" id="new-list-btn">
        </form>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Provider</th>
                <th scope="col">Brand</th>
                <th scope="col">Location</th>
                <th scope="col">CPU</th>
                <th scope="col">Drive</th>
                <th scope="col">Price ($)</th>
                <th scope="col">Operations</th>
            </tr>
            </thead>
            <tbody id="pricelist">

            </tbody>
        </table>
    </div>

    <script>
        function readData() {
            $.ajax({
                url: 'api/admin_pricelist',
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + appConfig.token);
                },
                success: function (data) {
                    $('#signin_form').hide();
                    if ('data' in data) {
                        let serverList = data['data'];
                        let tableData = '';
                        serverList.forEach((item) => {
                            tableData += '<tr><td><input type="text" id="provider' + item.id + '" value="' + item.provider + '">'
                                + '</td><td><input type="text" id="brand' + item.id + '" value="' + item.brand + '">'
                                + '</td><td><input type="text" id="location' + item.id + '" value="' + item.location + '">'
                                + '</td><td><input type="text" id="cpu' + item.id + '" value="' + item.cpu + '">'
                                + '</td><td><input type="text" id="drive' + item.id + '" value="' + item.drive + '">'
                                + '</td><td><input type="text" id="price' + item.id + '" value="' + (Math.round(item.price * 100) / 100) + '">'
                                + '</td><td><input type="button" value="Save" data="' + item.id + '" class="record-edit"> <input type="button" value="Delete" data="' + item.id + '" class="record-delete"></td></tr>'
                        })
                        $('#pricelist').html(tableData);
                        $(".record-edit").click((data) => {
                            editRow($(data.target).attr('data'));
                        });
                        $(".record-delete").click((data) => {
                            deleteRow($(data.target).attr('data'));
                        });
                    } else {
                        $('#pricelist').html('<tr><td colspan="6">Oops! Something happend: ' + JSON.stringify(data) + '</td></tr>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#restricted-zone').hide();
                    $('#signin_form').show();
                }
            });
        }

        validateUser();
        $('#register-group').hide();
        $('#register-primary-btn').hide();
        $('#user-name-group').hide();
        $('#signin-btn').click(() => {
            $.ajax({
                type: "POST",
                url: 'api/login',
                data:
                    {
                        email: $('#userEmail').val(),
                        password: $('#userPassword').val(),
                    },
                dataType: 'text',
                success: function (data) {
                    let response = JSON.parse(data);
                    $('#signin_form').hide();
                    if (response !== null && response.hasOwnProperty('token')) {
                        appConfig.token = response.token;
                        document.cookie = 'signin_token=' + response.token;
                        $('#restricted-zone').show();
                        $('#signin_form').hide();
                        readData();
                    } else {
                        $('#restricted-zone').hide();
                        $('#signin_form').show();
                        $('#register-group').hide();
                        $('#register-primary-btn').hide();
                        $('#user-name-group').hide();
                        console.log(data);
                        alert('Something went wrong!')
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#restricted-zone').hide();
                    $('#signin_form').show();
                    $('#register-group').hide();
                    $('#register-primary-btn').hide();
                    $('#user-name-group').hide();
                    alert('error: Something went wrong!')
                }
            });
        });
        $('#register-btn').click(() => {
            $('#register-group').show();
            $('#register-primary-btn').show();
            $('#user-name-group').show();
            $('#signin-btn').hide();
            $('#register-btn').hide();
        });
        $('#register-primary-btn').click(() => {
            $.ajax({
                type: "POST",
                url: 'api/register',
                data:
                    {
                        name: $('#userName').val(),
                        email: $('#userEmail').val(),
                        password: $('#userPassword').val(),
                        password_confirmation: $('#userPasswordConfirmation').val()
                    },
                dataType: 'text',
                success: function (data) {
                    $('#signin_form').hide();
                    let response = JSON.parse(data);
                    if (response !== null && response.hasOwnProperty('token')) {
                        appConfig.token = response.token;
                        document.cookie = 'signin_token=' + response.token;
                        $('#restricted-zone').show();
                        $('#signin_form').hide();
                        readData();
                    } else {
                        $('#restricted-zone').hide();
                        $('#signin_form').show();
                        $('#register-group').hide();
                        $('#register-primary-btn').hide();
                        $('#user-name-group').hide();
                        console.log(data);
                        alert('Something went wrong!')
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#restricted-zone').hide();
                    $('#signin_form').show();
                    $('#register-group').hide();
                    $('#register-primary-btn').hide();
                    $('#user-name-group').hide();
                    alert('Something went wrong!')
                }
            });
        });
        readData();

        function editRow(id) {
            $.ajax({
                url: 'api/admin_pricelist/' + id,
                type: "PUT",
                dataType: 'json',
                data: {
                    data: JSON.stringify({
                        provider: $('#provider' + id).val(),
                        brand: $('#brand' + id).val(),
                        location: $('#location' + id).val(),
                        cpu: $('#cpu' + id).val(),
                        drive: $('#drive' + id).val(),
                        price: (Math.round((+$('#price' + id).val()) * 100) / 100)
                    })
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + appConfig.token);
                },
                success: function (data) {
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();
                }
            });
        }

        function deleteRow(id) {
            console.log('delete' + id);
            location.reload();
        }

        $('#new-list-btn').click(() => {
            let fd = new FormData();
            let files = $('#newList')[0].files[0];
            fd.append('file', files);

            $.ajax({
                url: 'api/servers',
                type: 'post',
                data: fd,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + appConfig.token);
                },
                contentType: false,
                processData: false,
                success: function (response) {
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    location.reload();
                }
            });
        });
    </script>
@endsection
