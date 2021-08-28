@extends('layouts.app')

@section('content')

	<div class="col-lg-12">
		<h1 class="page-header">Edit Payroll : {{ $payroll->employee->name }}</h1>
	</div>
		@if($payroll->employee->full_time)
			<p><b>Full-Time</b> :  Yes</p>
			<p><b>Base Salary</b>: {{ $payroll->employee->role->salary }}</p>
		@else
			<p><b>Part-Time<b> : Yes</p>
			<br>
			<p><b>Base Salary<b>: 0</p>
		@endif
		
		<form action="{{ route('payrolls.update',['id'=>$payroll->id])}}" method="POST"
			class="form-horizontal">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}

			<div class="form-group">
				<label class="control-label col-md-1" for="over_time">Bank:</label>
				<div class="col-md-4">
					<select name="bank" id="bank" class="form-control">
						@foreach($banks as $item)
							<option value="{{ $item }}">{{ $item }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-1" for="account">Account Name : </label>
				<div class="col-md-4">
					<input type="text" name="account_name" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-1" for="account">Account Number : </label>
				<div class="col-md-4">
					<input type="text" name="account_no" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 text-center">
				<button class="btn btn-success" type="submit" >Update</button>
			</div>
		</form> 

@endsection
