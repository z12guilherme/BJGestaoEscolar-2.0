@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Escolas</h1>
    <a href="{{ route('schools.create') }}" class="btn btn-primary">Nova Escola</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($escolas as $escola)
            <tr>
                <td>{{ $escola->id }}</td>
                <td>{{ $escola->nome }}</td>
                <td>{{ $escola->endereco }}</td>
                <td>
                    <a href="{{ route('schools.show', $escola) }}" class="btn btn-info">Ver</a>
                    <a href="{{ route('schools.edit', $escola) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('schools.destroy', $escola) }}" method="POST" style="display:inline;">
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
