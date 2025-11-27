<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Http\Resources\ClassroomResource;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the classrooms.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $classrooms = Classroom::paginate(15);
        return ClassroomResource::collection($classrooms);
    }

    /**
     * Store a newly created classroom in storage.
     *
     * @param  \App\Http\Requests\StoreClassroomRequest  $request
     * @return \App\Http\Resources\ClassroomResource
     */
    public function store(StoreClassroomRequest $request)
    {
        $classroom = Classroom::create($request->validated());
        return new ClassroomResource($classroom);
    }

    /**
     * Display the specified classroom.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \App\Http\Resources\ClassroomResource
     */
    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified classroom in storage.
     *
     * @param  \App\Http\Requests\UpdateClassroomRequest  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \App\Http\Resources\ClassroomResource
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return new ClassroomResource($classroom);
    }

    /**
     * Remove the specified classroom from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return response()->json(['message' => 'Classroom deleted successfully']);
    }
}