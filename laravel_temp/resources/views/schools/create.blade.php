@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Escola</h1>
    <form action="{{ route('schools.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('schools.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
