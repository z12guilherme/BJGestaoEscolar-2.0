<?php

namespace App\Http\Controllers;

use App\Models\Escola;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $escolas = Escola::all();
        return view('schools.index', compact('escolas'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
        ]);

        Escola::create($request->all());

        return redirect()->route('schools.index')->with('success', 'Escola criada com sucesso.');
    }

    public function show(Escola $escola)
    {
        return view('schools.show', compact('escola'));
    }

    public function edit(Escola $escola)
    {
        return view('schools.edit', compact('escola'));
    }

    public function update(Request $request, Escola $escola)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
        ]);

        $escola->update($request->all());

        return redirect()->route('schools.index')->with('success', 'Escola atualizada com sucesso.');
    }

    public function destroy(Escola $escola)
    {
        $escola->delete();

        return redirect()->route('schools.index')->with('success', 'Escola removida com sucesso.');
    }
}
