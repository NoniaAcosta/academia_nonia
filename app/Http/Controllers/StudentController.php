<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Student::all(), 200);
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

        $student = Student::create($validated);
        return response()->json(['message' => 'Registro insertado correctamente', 'registro' => $student], 201);
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

        $student->update($validated);
        return response()->json(['message' => 'Registro actualizado correctamente', 'datos' => $student], 200);
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

        $student->delete();
        return response()->json(['message' => 'Estudiante eliminado'], 200);
    }
}
