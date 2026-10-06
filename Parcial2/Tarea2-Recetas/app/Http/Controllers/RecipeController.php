<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->recipes();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $recipes = $query->latest()->paginate(9)->withQueryString();

        return view('recipes.index', compact('recipes'));
    }

    public function create()
    {
        return view('recipes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRecipe($request);

        $request->user()->recipes()->create($validated);

        return redirect()->route('recipes.index')->with('success', '¡Receta creada con éxito!');
    }

    public function show(Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);
        return view('recipes.show', compact('recipe'));
    }

    public function edit(Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);
        return view('recipes.edit', compact('recipe'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);

        $validated = $this->validateRecipe($request);
        $recipe->update($validated);

        return redirect()->route('recipes.index')->with('success', '¡Receta actualizada con éxito!');
    }

    public function destroy(Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);
        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', '¡Receta eliminada con éxito!');
    }

    private function authorizeRecipe(Recipe $recipe): void
    {
        if ($recipe->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver o modificar esta receta.');
        }
    }

    private function validateRecipe(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['desayuno', 'almuerzo', 'cena', 'postre', 'bebida'])],
            'cooking_time' => ['required', 'integer', 'min:1'],
            'difficulty' => ['required', Rule::in(['baja', 'media', 'alta'])],
            'ingredients' => ['required', 'string'],
            'steps' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ], [
            'title.required' => 'El título de la receta es obligatorio.',
            'category.required' => 'Debes seleccionar una categoría.',
            'category.in' => 'La categoría seleccionada no es válida.',
            'cooking_time.required' => 'El tiempo de preparación es obligatorio.',
            'cooking_time.integer' => 'El tiempo debe ser un número entero.',
            'cooking_time.min' => 'El tiempo de preparación debe ser mayor a 0 minutos.',
            'difficulty.required' => 'Debes indicar el nivel de dificultad.',
            'difficulty.in' => 'La dificultad debe ser baja, media o alta.',
            'ingredients.required' => 'Debes ingresar al menos un ingrediente.',
            'steps.required' => 'Debes ingresar los pasos de preparación.',
        ]);
    }
}