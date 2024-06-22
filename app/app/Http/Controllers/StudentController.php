<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Filtering
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }
        if ($request->has('course')) {
            $query->where('course', $request->course);
        }
        if ($request->has('section')) {
            $query->where('section', $request->section);
        }
        
        // Sorting
        if ($request->has('sort')) {
            $query->orderBy($request->sort);
        }
        
        // Limiting and offset
        if ($request->has('limit')) {
            $query->limit($request->limit);
        }
        if ($request->has('offset')) {
            $query->offset($request->offset);
        }
        
        // Select fields
        if ($request->has('fields')) {
            $query->select(explode(',', $request->fields));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'birthdate' => 'required|date_format:Y-m-d',
            'sex' => 'required|in:MALE,FEMALE',
            'address' => 'required|string',
            'year' => 'required|integer',
            'course' => 'required|string',
            'section' => 'required|string',
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'string',
            'lastname' => 'string',
            'birthdate' => 'date_format:Y-m-d',
            'sex' => 'in:MALE,FEMALE',
            'address' => 'string',
            'year' => 'integer',
            'course' => 'string',
            'section' => 'string',
        ]);

        $student->update($validated);

        return response()->json($student);
    }
}

