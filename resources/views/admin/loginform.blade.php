<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PO APP') }}</title>

    <!-- Scripts -->
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
				{{ config('app.name', 'PO APP') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">

			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="card">
							<div class="card-header">{{ __('Login') }}</div>

							<div class="flash-message">
                                    @if(session()->has('status'))
                                        @if(session()->get('status') == 'Success')
                                            <div class="alert alert-success  alert-dismissible">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session()->get('message') }}
                                            </div>
                                        @endif
                                        @if(session()->get('status') == 'Error')
                                            <div class="alert alert-danger  alert-dismissible">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session()->get('message') }}
                                            </div>
                                        @endif
                                    @endif
                                </div> <!-- end .flash-message -->
							<div class="card-body">
								<form method="POST" action="{{ route('login.check') }}" id="login_form">
									@csrf

									<div class="form-group row">
										<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

										<div class="col-md-6">
											<!--replace all email with username-->
											<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

											@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row">
										<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

										<div class="col-md-6">
											<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

											@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row">
										<div class="col-md-6 offset-md-4">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

												<label class="form-check-label" for="remember">
													{{ __('Remember Me') }}
												</label>
											</div>
										</div>
									</div>

									<div class="form-group row mb-0">
										<div class="col-md-8 offset-md-4">
											<button type="submit" class="btn btn-primary">
												{{ __('Login') }}
											</button>
											<a class="btn btn-link" href="{{ route('resetpassword') }}">
                                        		{{ __('Forgot Your Password?') }}
                                    		</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
        </main>
    </div>
</body>
</html>

<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script>
$( document ).ready(function() {
	$("form[id='login_form']").validate({
		// Specify validation rules
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
			}
		},
		// Specify validation error messages
		messages: {
			email: {
				required: 'Email address is required',
				email: 'Provide a valid Email address',
			},
			password: {
				required: 'Password is required',
				
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>

