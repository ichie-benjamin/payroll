@extends('layouts.app')

@section('content')

	<div class="col-md-12">
		@if(Session::has('success'))
			<div class="alert alert-success">
				<span class="glyphicon glyphicon-ok"></span>
				{!! session('success') !!}

				<button type="button" class="close" data-dismiss="alert" aria-label="close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
		@endif
	</div>

	<div class="col-md-6">
	<div class="row">
		<h1 class="page-header">Payroll : #{{ $payroll->id }} payments</h1>
	</div>
		@if($employee->full_time)
			<p><b>Full-Time</b> :  Yes</p>
			<p><b>Base Salary</b>: {{ $employee->role->salary }}</p>
		@else
			<p><b>Part-Time<b> : Yes</p>
			<br>
			<p><b>Base Salary<b>: 0</p>
		@endif

		<form action="{{ route('payrolls.payment.store',['id'=>$payroll->id])}}" method="POST"
			class="form-horizontal">
				{{ csrf_field() }}

			<div class="form-group">
				<label class="control- col-md-3 pull-left" for="over_time">Payment Type:</label>
				<div class="col-md-12">
					<select name="type" id="type" class="form-control">
						<option value="allowance">Allowance</option>
						<option value="deduction">Deduction</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-1" for="hours">Title: </label>
				<div class="col-md-12">
					<input type="text" name="title" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-1" for="rate">Amount: </label>
				<div class="col-md-12">
					<input type="number" name="amount" class="form-control">
				</div>
			</div>

			<div class="col-lg-12 text-center">
				<button class="btn btn-success" type="submit" >Submit</button>
			</div>
		</form>
	</div>

	<div class="col-md-6">
		<table class= "table table-hover" id="filterTable">
			<thead>
			<th>Type</th>
			<th>Title</th>
			<th>Amount</th>
			<th>Trash</th>
			</thead>

			<tbody>
			@if($payroll->payments->count()> 0)
				@foreach($payroll->payments as $payment)
					<tr>
						<td class="text-capitalize">{{ $payment->type }}</td>
						<td>{{ $payment->title }}</td>
						<td>₦{{ number_format($payment->amount) }}</td>
						<td>
							<form action="{{ route('payrolls.destroy', ['id' => $payment->id]) }}" method="POST">
								{{csrf_field() }}
								{{method_field('DELETE')}}
								<button class="btn btn-danger">Delete</button>
							</form>
						</td>
					</tr>
				@endforeach

				<tr>
					<td class="text-capitalize">BASIC SALARY</td>
					<td class="text-capitalize"></td>
					<td class="text-capitalize">₦{{ number_format($employee->role->salary) }}</td>
				</tr>
				<tr>
					<td class="text-capitalize">ALLOWANCES</td>
					<td class="text-capitalize"></td>
					<td class="text-capitalize">₦{{ number_format($payroll->allowances()) }}</td>
				</tr>
				<tr>
					<td class="text-capitalize "><strong>GROSS INCOME</strong></td>
					<td class="text-capitalize"></td>
					<td class="text-capitalize">₦{{ number_format($payroll->grossincome()) }}</td>
				</tr>
				<tr>
					<td class="text-capitalize">DEDUCTIONS</td>
					<td class="text-capitalize"></td>
					<td class="text-capitalize">₦{{ number_format($payroll->deductions()) }}</td>
				</tr>
				<tr>
					<td class="text-capitalize"><strong>NET INCOME</strong></td>
					<td class="text-capitalize"></td>
					<td class="text-capitalize">₦{{ number_format($payroll->netIncome()) }}</td>
				</tr>
				<tr>
					<td class="text-capitalize"><strong>Account </strong></td>
					<td class="text-capitalize">{{ $payroll->bank }}</td>
					<td class="text-capitalize">{{ $payroll->account_name }} ({{ $payroll->account_no }})</td>
					<td>
						@if ($payroll->paid)
							<a href="" class="btn btn-success" disabled="">Paid</a> </td>
				@else
						<a href="{{ route('payrolls.pay',$payroll->id) }}" class="btn btn-primary">Pay Now</a> </td>
					@endif
				</tr>
			@else
				<tr>
					<th colspan="5" class="text-center">Empty</th>
				</tr>
			@endif
			</tbody>
		</table>

	</div>
@endsection
