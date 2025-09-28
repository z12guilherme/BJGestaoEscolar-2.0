@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Professor</h1>
    <form action="{{ route('professores.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="subject">Disciplina</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone">
        </div>
        <div class="form-group">
            <label for="escola_id">Escola</label>
            <select class="form-control" id="escola_id" name="escola_id">
                <option value="">Selecione</option>
                @foreach(\App\Models\Escola::all() as $escola)
                <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Criar</button>
        <a href="{{ route('professores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
