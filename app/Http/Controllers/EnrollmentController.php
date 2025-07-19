<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $enrollment = Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'enrolled_at' => now(),
        ]);
        try {
            return response()->json(['message' => 'Inscripción realizada correctamente', 'data' => $enrollment], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear inscripción: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al guardar la inscripción.'], 500);
        }
    }

    public function index(Request $request)
    {
        if ($request->has('student_id')) {
            return Enrollment::where('student_id', $request->student_id)->with('course')->get();
        }

        if ($request->has('course_id')) {
            return Enrollment::where('course_id', $request->course_id)->with('student')->get();
        }

        return response()->json(['error' => 'Debe enviar student_id o course_id'], 400);
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json(['error' => 'Inscripción no encontrada'], 404);
        }
        try {
            $enrollment->delete();
            return response()->json(['message' => 'Inscripción eliminada']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar inscripcion: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al eliminar la inscripción.'], 500);
        }
    }
}
