@extends('emails.master')

@section('body')
	<p>Olá,</p>
	<p>O(a) usuário(a) {{ $user->name }}, sugeriu as seguintes atualizações:</p>
	@if (!empty($fields))
		<ul>
		@foreach ($fields as $field)
			<li>
				<strong>{{ $field['fieldName'] }}:</strong> {{ $field['correctedValue'] }}
			</li>
		@endforeach
		</ul>
	@endif
	@if (!empty($obs))
		<p><strong>Observações: </strong> {{ $obs }}</p>
	@endif

	<h2>Ficha do Político</h2>
	<p><strong>Código:</strong> {{ $politician->id }}</p>
	<p><strong>Apelido:</strong> {{ $politician->nickname }}</p>
	<p><strong>Nome:</strong> {{ $politician->name }}</p>
	<p><strong>Cargo:</strong> {{ $politician->position()->first()->name }}</p>

@stop