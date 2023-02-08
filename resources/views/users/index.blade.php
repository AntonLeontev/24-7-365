@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
	<div class="container">
		@foreach ($users as $user)
			<div class="row" href="{{ route('users.show', $user->id) }}">
				<div class="col">
					<a href="{{ route('users.show', $user->id) }}">
						{{ $user->first_name }} {{ $user->last_name }}
					</a>
				</div>
				<div class="col">{{ $user->email }}</div>
				<div class="col">
					@if($user->roles->count() === 0)
						Роль не назначена
					@else
						@foreach ($user->roles as $role)
							{{ $role->name }} 
						@endforeach
					@endif
					
				</div>				
			</div>
		@endforeach
		{{ $users->links() }}
	</div>
@endsection
