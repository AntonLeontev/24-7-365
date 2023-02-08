@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
	<div class="container">
		@foreach ($users as $user)
			<a class="row" href="{{ route('users.show', $user->id) }}">
				<div class="col">{{ $user->first_name }} {{ $user->last_name }}</div>
				<div class="col">{{ $user->email }}</div>
				<div class="col">
					@foreach ($user->roles as $role)
						{{ $role->name }} 
					@endforeach
				</div>
			</a>
		@endforeach
		{{ $users->links() }}
	</div>
@endsection
