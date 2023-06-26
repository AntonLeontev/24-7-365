@extends('layouts.app')

@section('title', 'Новости')

@section('content')
	<x-common.tables.yellow>
		<x-slot:header>
			<div class="col">Заголовок</div>
			<div class="col-2">Обложка</div>
			<div class="col-1"></div>
			<div class="col">Дата создания</div>
		</x-slot:header>

		@foreach ($articles as $article)
			<x-common.tables.yellow.row>
				<div class="col">{{ $article->title }}</div>
				<div class="col-2"></div>
				<div class="col-1">{{ $article->active }}</div>
				<div class="col">{{ $article->created_at->format('d.m.Y') }}</div>
			</x-common.tables.yellow.row>
		@endforeach

		@if ($articles->isEmpty())
			<x-common.tables.yellow.row>
				<div class="col">Новостей пока нет</div>
			</x-common.tables.yellow.row>
		@endif
	</x-common.tables.yellow>
	{{ $articles->links() }}
@endsection
