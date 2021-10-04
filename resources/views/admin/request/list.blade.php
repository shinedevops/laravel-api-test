@extends('admin.layouts.cmlayout')

@section('body')
<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Login Request List</h1>
	</div>
	<div class="flash-message">
		@if(session()->has('status'))
			@if(session()->get('status') == 'success')
				<div class="alert alert-success  alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session()->get('message') }}
				</div>
			@endif
		@endif
	</div> <!-- end .flash-message -->
	<div class="row">
        <div class="col-xl-12 col-md-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<form class="form-inline float-left" id="search-form">
						<div class="form-group">
							<input type="text" class="form-control" data-model="User" data-searchcoulnm="first_name,last_name,email" id="search_keyword" name="search_keyword" placeholder="What are you looking for?">
						</div>
						<button type="submit" class="btn btn-primary ml-10">Search</button>
					</form>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover dt-responsive nowrap">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Name</th>
									<th>Email</th>
									<th>Created Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($data as $key => $row)
								
								<tr>
									<td>{{$key+1}}</td>
									<td>{{$row->user ? $row->user->name : 'N/A' }}</td>
									<td>{{$row->user ? $row->user->email : 'N/A' }}</td>
									<td>{{$row->created_at ? $row->created_at : 'N/A'}}</td>
									<td>
									<a class="anchorLess">
									   <a title="Click to Accept" href="#" data-id="{{$row->id}}" class="anchorLess accept-request"><i class="fas fa-check-square text-success"></i></a>
									   <a title="Click to Reject" class="anchorLess delete-confirm" href="{{route('request.status', ['id' => $row->id, 'status' => 2])}}" ><i class="fas fa-window-close text-danger"></i></a>
									</a>    
									</td>
								</tr>
								@endforeach
								@if($data->count() == 0)
								<tr>
									<td colspan="10" class="text-center text-dabger">No request to display.</td>
								</tr>
								@endif
							</tbody>
						</table>
						{{ $data->appends(request()->except('page'))->links() }}
						

						<p>
							Displaying {{$data->count()}} of {{ $data->total() }} request(s).
						</p>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>
<style>
.no-margin{margin:0px;}
</style>

        <!-- Change PassWord Model -->
        <div class="modal fade" id="acceptRequest" tabindex="-1" role="dialog" aria-labelledby="acceptRequestLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleDepP">Approved Request</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="user" action="{{ route('request.update') }}" method="post" id="acceptRequestSubmit">
                        @csrf
						<input type="hidden" id="rowId" name="id" value="">
                        <div class="modal-body">
                            <div id="successMsgexpiry"></div>
                            <div id="errorsDeprtexpiry"></div>
                            <div class="form-group">
								<label for="">Valid Only for(Time in minutes)</label>
                                <input type="number" name="expiry" id="expiry" class="form-control form-control-user"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="savedBtnexpiry">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Change PassWord model -->
<!-- /.container-fluid -->
@endsection

@section('scripts')
@stop