@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Professor</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $professor->name }}</h5>
            <p class="card-text"><strong>Disciplina:</strong> {{ $professor->subject }}</p>
            <p class="card-text"><strong>Telefone:</strong> {{ $professor->telefone }}</p>
            <p class="card-text"><strong>Escola:</strong> {{ $professor->escola ? $professor->escola->nome : 'N/A' }}</p>
            <p class="card-text"><strong>ID:</strong> {{ $professor->id }}</p>
            <p class="card-text"><strong>Criado em:</strong> {{ $professor->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <a href="{{ route('professores.index') }}" class="btn btn-secondary">Voltar</a>
    <a href="{{ route('professores.edit', $professor) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
