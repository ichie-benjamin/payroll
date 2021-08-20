@extends('layouts.app')


@section('content')
	<div class="col-lg-6 col-lg-offset-3">
		<h1 class="page-header text-center">New Department</h1>

	

	<form action ="{{ route('departments.store') }}" method="POST">
		{{ csrf_field() }}
		
		<div class="form-group row">
			<label for="name">Name</label>
			<input type="text" name="name" class="form-control">
		</div>
		
		<div class="form-group row">
			<button class ="btn.btn.success" type="submit">Save Department</button>			
		</div>
		
	</form>

	</div>
@endsection

