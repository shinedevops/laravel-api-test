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
            <h2 class="text-center">Request for login</h2>
            <form class="form-horizontal" id="UserLoginForm" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Send Request</button>
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
                    button = document.getElementById("submitBtn")
                    
                    button.innerHTML = 'Sending <i class="fa fa-spinner" aria-hidden="true"></i>';
                    button.disabled = true;
                    fetch('http://localhost/shineDezign/portal-app/public/api/v1/login-request',{
                        method: "post",
                        body: JSON.stringify({'email': email}),
                        headers : {'Content-Type': 'application/json'}
                    })
                    .then(async (resp) => {
                        if(resp.status >= 400 && resp.status < 600 ) throw new Error(JSON.stringify(await resp.json()))
                        return resp.json()
                    })
                    .then((response) => {
                        toastr.success(response.message);
                        button.innerHTML = 'Send Request';
                        button.disabled = false;
                    })
                    .catch((err) => {
                        err = JSON.parse(err.message)
                        toastr.error(err.message);
                    })
                }
            });
        });
        </script>
    </body>
</html>