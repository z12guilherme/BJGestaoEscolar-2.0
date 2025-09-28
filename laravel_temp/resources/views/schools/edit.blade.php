@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Escola</h1>
    <form action="{{ route('schools.update', $escola) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ $escola->nome }}" required>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value="{{ $escola->endereco }}">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('schools.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
