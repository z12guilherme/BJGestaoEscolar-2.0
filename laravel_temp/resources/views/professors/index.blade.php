@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Professores</h1>
    <a href="{{ route('professores.create') }}" class="btn btn-primary">Novo Professor</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Disciplina</th>
                <th>Telefone</th>
                <th>Escola</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($professores as $professor)
            <tr>
                <td>{{ $professor->id }}</td>
                <td>{{ $professor->name }}</td>
                <td>{{ $professor->subject }}</td>
                <td>{{ $professor->telefone }}</td>
                <td>{{ $professor->escola ? $professor->escola->nome : 'N/A' }}</td>
                <td>
                    <a href="{{ route('professores.show', $professor) }}" class="btn btn-info">Ver</a>
                    <a href="{{ route('professores.edit', $professor) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('professores.destroy', $professor) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Remover</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
