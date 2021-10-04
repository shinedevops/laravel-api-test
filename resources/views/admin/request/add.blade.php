@extends('admin.layouts.cmlayout')

@section('body')
                <div class="container-fluid">
                    <div class="row page-title  no-display">
                        <div class="col-md-12">
                            <h4 class="mb-1 mt-0">Add User Details</h4>
                        </div>
                    </div>
					<div class="flash-message">
						@if(session()->has('status'))
							@if(session()->get('status') == 'error')
								<div class="alert alert-danger  alert-dismissible">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ session()->get('message') }}
								</div>
							@endif
							@if(session()->get('status') == 'success')
								<div class="alert alert-success  alert-dismissible">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ session()->get('message') }}
								</div>
							@endif
						@endif
					</div> <!-- end .flash-message -->
                    <div class="row mt-4">
						<div class="col-md-12">
                            <div class="card">
                                <div class="card-body pt-2 pb-3 manageClinicSection">
									<form action="{{route('user.create')}}" method="post" class="user" name="edit_user_form" enctype="multipart/form-data">
										@csrf																	
											<div class="row">
												<div class="col-lg-4 col-md-6 col-12">
													<div class="form-group">
														<label>Name<span class="required">*</span></label>
														<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control form-control-user" />
														@if ($errors->has('name'))
															<span class="text-danger">{{ $errors->first('name') }}</span>
														@endif
													</div>
												</div>
											</div>															
											<div class="row">
												<div class="col-lg-4 col-md-6 col-12">
													<div class="form-group">
														<label>Email<span class="required">*</span></label>
														<input type="text" name="email" id="email" value="{{old('email')}}" class="form-control form-control-user" />
														@if ($errors->has('email'))
															<span class="text-danger">{{ $errors->first('email') }}</span>
														@endif
													</div>
												</div>
											</div>					
											<div class="row">  
												<div class="col-lg-4 col-md-6 col-12">
													<div class="form-group">
														<label>Status<span class="required">*</span></label>
														<div class="input-group">
															<div id="radioBtn" class="btn-group">
																<a class="btn btn-success btn-sm {{ old('status') == '1' ? 'active' : 'notActive'}}" data-toggle="status" data-title="1">Enabled</a>
																<a class="btn btn-danger btn-sm {{ old('status') == '0' ? 'active' : 'notActive'}}" data-toggle="status" data-title="0">Disabled</a>
															</div>
															<input type="hidden" name="status" id="status" value="{{ old('status') == '1' ? '1' : '0'}}">
														</div>
														@if ($errors->has('status'))
															<span class="text-danger">{{ $errors->first('status') }}</span>
														@endif
													</div>
												</div>									
											</div>					
											<div class="mt-1 mb-1">
												<div class="text-left d-print-none mt-4">
													<button type="submit" name="action" value="saveadd" class="btn btn-primary">Save & Add New</button>
													<button type="submit" name="action" value="save"  class="btn btn-primary">Save</button>
													<a href="{{route('users.list')}}" class="btn btn-light">Cancel</a>
												</div>
												
											</div>
									</form>
								</div>                          
							</div>
						</div>
						<!-- end row -->
					</div> 
					<!-- container-fluid -->
					</div> 
@endsection
@section('scripts')
  
<script>
$( document ).ready(function() {
	$("form[name='edit_user_form']").validate({
		// Specify validation rules
		rules: {
			name: "required",
			email: {
				required: true,
				email: true,
			},
		},
		// Specify validation error messages
		messages: {
			name: "Please enter first name",
			email: {
				required: 'Email address is required',
				email: 'Please enter a valid email address'
			},
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>
@stop