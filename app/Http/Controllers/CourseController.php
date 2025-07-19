<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Listar cursos completos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Course::all(), 200);
    }

    /**
     * Registrar Cursos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        if (Course::existeDuplicado($validated)) {
            return response()->json([
                'message' => 'Ya existe un curso con estos datos.'
            ], 422);
        }
        try {
            $course = Course::create($validated);
            return response()->json(['message' => 'Registro insertado correctamente', 'data' => $course], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear curso: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al guardar el curso.'], 500);
        }
    }

    /**
     * Listar cursos por id
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
        return response()->json($course, 200);
    }

   /**
    * Actualizar cursos por id
    *
    * @param Request $request
    * @param integer $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function update(Request $request, int $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);
        if (Course::existeDuplicado($validated, $id)) {
            return response()->json([
                'message' => 'Ya existe un curso con estos datos.'
            ], 422);
        }
        try {
            $course->update($validated);
            return response()->json(['message' => 'Registro actualizado correctamente', 'data' => $course], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar curso: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al actualizar el curso.'], 500);
        }
    }

    /**
     * Eliminar cursos por id
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
        try {
            $course->delete();
            return response()->json(['message' => 'Curso eliminado'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar curso: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al eliminar el curso.'], 500);
        }
    }
}
