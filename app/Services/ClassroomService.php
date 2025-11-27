<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\User;

class ClassroomService extends Service
{
    /**
     * Create a new classroom.
     *
     * @param array $data
     * @return Classroom
     */
    public function createClassroom($data)
    {
        return Classroom::create($data);
    }

    /**
     * Update an existing classroom.
     *
     * @param Classroom $classroom
     * @param array $data
     * @return Classroom
     */
    public function updateClassroom(Classroom $classroom, $data)
    {
        $classroom->update($data);
        return $classroom;
    }

    /**
     * Delete a classroom.
     *
     * @param Classroom $classroom
     * @return bool
     */
    public function deleteClassroom(Classroom $classroom)
    {
        // Delete related schedules first
        $classroom->schedules()->delete();
        
        // Delete the classroom
        return $classroom->delete();
    }

    /**
     * Assign students to a classroom.
     *
     * @param Classroom $classroom
     * @param array $studentIds
     * @return Classroom
     */
    public function assignStudents(Classroom $classroom, $studentIds)
    {
        // Get student users
        $students = User::whereIn('id', $studentIds)
            ->whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->get();
            
        // Sync students to classroom
        $classroom->students()->sync($students->pluck('id'));
        
        return $classroom;
    }

    /**
     * Assign teachers to a classroom.
     *
     * @param Classroom $classroom
     * @param array $teacherIds
     * @return Classroom
     */
    public function assignTeachers(Classroom $classroom, $teacherIds)
    {
        // Get teacher users
        $teachers = User::whereIn('id', $teacherIds)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['teacher', 'staff']);
            })
            ->get();
            
        // Sync teachers to classroom
        $classroom->teachers()->sync($teachers->pluck('id'));
        
        return $classroom;
    }

    /**
     * Get classroom statistics.
     *
     * @param Classroom $classroom
     * @return array
     */
    public function getStatistics(Classroom $classroom)
    {
        $studentCount = $classroom->students()->count();
        $teacherCount = $classroom->teachers()->count();
        $scheduleCount = $classroom->schedules()->count();
        
        return [
            'student_count' => $studentCount,
            'teacher_count' => $teacherCount,
            'schedule_count' => $scheduleCount,
        ];
    }
}