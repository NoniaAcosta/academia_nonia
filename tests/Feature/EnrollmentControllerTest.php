<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;

class EnrollmentControllerTest extends TestCase
{
    /** @test */
    public function puede_crear_una_inscripcion()
    {
        // Crear student y course de prueba, user
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $user = User::factory()->create();
        // Datos que se enviarán
        $data = [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ];

        // Enviar solicitud POST al endpoint
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/enrollments', $data);

        // Verificar que el response fue exitoso
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => ['id', 'student_id', 'course_id', 'enrolled_at', 'created_at', 'updated_at'],
        ]);

        // Verificar que exista en la base de datos
        $this->assertDatabaseHas('enrollments', $data);
    }
    /** @test */
    public function puede_eliminar_una_inscripcion()
    {
        // Crear student, course y enrollment, user
        $student = \App\Models\Student::factory()->create();
        $course = \App\Models\Course::factory()->create();
        $user = User::factory()->create();

        $enrollment = \App\Models\Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        // Hacer la solicitud DELETE
        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/enrollments/{$enrollment->id}");

        // Verificar que fue exitosa
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Inscripción eliminada',
        ]);

        // Verificar que ya no está en la base de datos
        $this->assertDatabaseMissing('enrollments', [
            'id' => $enrollment->id,
        ]);
    }
    /** @test */
    public function devuelve_404_si_la_inscripcion_no_existe_al_eliminar()
    {
        $user = User::factory()->create();
        $response =  $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/enrollments/1'); // ID inexistente

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Inscripción no encontrada',
        ]);
    }
}
