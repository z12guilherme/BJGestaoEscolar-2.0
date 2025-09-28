<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Escola;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index()
    {
        $professores = Professor::with('escola')->get();
        return view('professores.index', compact('professores'));
    }

    public function create()
    {
        $escolas = Escola::all();
        return view('professores.create', compact('escolas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'escola_id' => 'nullable|exists:escolas,id',
            'telefone' => 'nullable|string|max:20',
        ]);

        Professor::create($request->all());

        return redirect()->route('professores.index')->with('success', 'Professor criado com sucesso.');
    }

    public function show(Professor $professor)
    {
        return view('professores.show', compact('professor'));
    }

    public function edit(Professor $professor)
    {
        $escolas = Escola::all();
        return view('professores.edit', compact('professor', 'escolas'));
    }

    public function update(Request $request, Professor $professor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'escola_id' => 'nullable|exists:escolas,id',
            'telefone' => 'nullable|string|max:20',
        ]);

        $professor->update($request->all());

        return redirect()->route('professores.index')->with('success', 'Professor atualizado com sucesso.');
    }

    public function destroy(Professor $professor)
    {
        $professor->delete();

        return redirect()->route('professores.index')->with('success', 'Professor removido com sucesso.');
    }
}
