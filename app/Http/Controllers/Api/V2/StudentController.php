<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Retorna la lista de estudiantes por pagina
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Usa 10 si no se envía per_page
        $students = Student::paginate($perPage);
        return response()->json(
            [   'version'=>'2',
                'students'=>$students->items()
            ]
        );
    }

   /**
    * Registra un estudiante
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
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
     * Permite vizualizar estudiante por id
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        return response()->json($student, 200);
    }

    /**
     * Permite actualizar el registro por el Id del estudiante 
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
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
     * Permite Eliminar un estudiante por su ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
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
