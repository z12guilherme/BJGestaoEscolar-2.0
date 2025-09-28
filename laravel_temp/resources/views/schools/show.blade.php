@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes da Escola</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $escola->nome }}</h5>
            <p class="card-text"><strong>Endereço:</strong> {{ $escola->endereco }}</p>
            <p class="card-text"><strong>ID:</strong> {{ $escola->id }}</p>
            <p class="card-text"><strong>Criado em:</strong> {{ $escola->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <a href="{{ route('schools.index') }}" class="btn btn-secondary">Voltar</a>
    <a href="{{ route('schools.edit', $escola) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
