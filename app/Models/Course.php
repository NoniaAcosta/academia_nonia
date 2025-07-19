<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public static function existeDuplicado($datos, $exceptId = null)
    {
        $query = self::where('title', $datos['title'])
            ->where('description', $datos['description'])
            ->where('start_date', $datos['start_date'])
            ->where('end_date', $datos['end_date']);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}
