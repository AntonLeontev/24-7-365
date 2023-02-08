@extends('layouts.app')

@section('title', $user->first_name)

@section('content')
<div class="container">
	<div class="row">
		<div class="col">Profile {{ $user->first_name }}</div>
		<div class="col">
			@can('assign roles')
			<role-select 
				action="{{ route('users.update-role', $user->id) }}" 
				v-bind:roles="{{ roles() }}"
				v-bind:user-roles={{ $user->roles->pluck('name') }}
			>
			</role-select>
			@else
				@foreach ($user->roles as $role)
					{{ $role->name }}
				@endforeach
			@endcan
		</div>
		<div class="col"></div>
	</div>

</div>
	
@endsection
