<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Course::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $student = Course::create($validated);
        return response()->json(['message' => 'Registro insertado correctamente', 'datos' => $student], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Course::find($id);
        if (!$student) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
        return response()->json($student, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Course::find($id);
        if (!$student) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);

        $student->update($validated);
        return response()->json(['message' => 'Registro actualizado correctamente', 'datos' => $student], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Course::find($id);
        if (!$student) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $student->delete();
        return response()->json(['message' => 'Curso eliminado'], 200);
    }
}
