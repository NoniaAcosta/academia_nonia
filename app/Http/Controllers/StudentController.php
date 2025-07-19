<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Usa 10 si no se envía per_page
        $students = Student::paginate($perPage);
        return response()->json($students->items());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'birthdate' => 'required|date',
            'nationality' => 'required|string',
        ]);
        try {
            $student = Student::create($validated);
            return response()->json(['message' => 'Registro insertado correctamente', 'registro' => $student], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear estudiante: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al guardar el estudiante.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        return response()->json($student, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => "sometimes|email|unique:students,email,$id",
            'birthdate' => 'sometimes|date',
            'nationality' => 'sometimes|string',
        ]);
        try {
            $student->update($validated);
            return response()->json(['message' => 'Registro actualizado correctamente', 'data' => $student], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al actualizar el estudiante.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        try {
            $student->delete();
            return response()->json(['message' => 'Estudiante eliminado'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar estudiante: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al eliminar el estudiante.'], 500);
        }
    }
}
