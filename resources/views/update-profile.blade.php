@php 
header('Content-Type: application/octet-stream');
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login Portal</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h2 class="text-center">Welcome Back, {{ucfirst($user->name)}}</h2>
            <form class="form-horizontal" id="UserLoginForm" method="post">
            <input type="hidden" name="id" id="rowId" value="{{$user->id}}">
            <input type="hidden" name="uid" id="uid" value="{{$user->uid}}">
            <input type="hidden" name="token" id="token" value="{{$user->api_token}}">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="{{$user->email}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
        <script>
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '500',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        </script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
        <script>
        $( document ).ready(function() {
            $("form[id='UserLoginForm']").submit(function(e) {
                e.preventDefault();
            }).validate({
                // Specify validation rules
                ignore: '',
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: {
                        required: 'Email field is required',
                        email: 'Provide an valid email address'
                    },
                },
                submitHandler: async function(form) {
                    let email  = document.getElementById("email").value
                    let rowId  = document.getElementById("rowId").value
                    let uid  = document.getElementById("uid").value
                    let token  = document.getElementById("token").value
                    button = document.getElementById("submitBtn")
                    
                    button.innerHTML = 'Updating <i class="fa fa-spinner" aria-hidden="true"></i>';
                    button.disabled = true;
                    fetch('http://localhost/shineDezign/portal-app/public/api/v1/update-request',{
                        method: "post",
                        body: JSON.stringify({'id': rowId, 'email': email, 'uid': uid, 'token': token}),
                        headers : {'Content-Type': 'application/json'}
                    })
                    .then(async (resp) => {
                        if(resp.status >= 400 && resp.status < 600 ) throw new Error(JSON.stringify(await resp.json()))
                        return resp.json()
                    })
                    .then((response) => {
                        toastr.success(response.message);
                        button.innerHTML = 'Update';
                        button.disabled = false;
                        location.reload();
                    })
                    .catch((err) => {
                        err = JSON.parse(err.message)
                        toastr.error(err.message);
                    })
                }
            });
        });
        
        let uid  = document.getElementById("uid").value
        let token  = document.getElementById("token").value
        let url = 'http://localhost/shineDezign/portal-app/public/api/v1/login-check?uid='+uid+'&token='+token;
        function callme(){
            fetch(url,{
                method: "GET",
                headers : {'Content-Type': 'application/json'}
            })
            .then(async (resp) => {
                if(resp.status >= 400 && resp.status < 600 ) throw new Error(JSON.stringify(await resp.json()))
                return resp.json()
            })
            .then((response) => {
                if('error' == response.api_response){
                location.reload();
                }
            })
        }
        
        function startTimer() {
            console.log('start');
            timer = setInterval(function() { 
            console.log('start in interval');
                callme()
            }, 5000);
        }
        startTimer();
        </script>
    </body>
</html>