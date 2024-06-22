<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index(Request $request, $id)
    {
        $query = Subject::where('student_id', $id);

        // Apply filters
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('remarks')) {
            $query->where('remarks', $request->remarks);
        }

        if ($request->has('sort')) {
            $query->orderBy($request->sort);
        }

        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 0;

        $subjects = $query->limit($limit)->offset($offset)->get();

        return response()->json([
            'metadata' => [
                'count' => $subjects->count(),
                'search' => $request->search,
                'limit' => $limit,
                'offset' => $offset,
                'fields' => $request->fields ?? []
            ],
            'subjects' => $subjects
        ]);
    }

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'subject_code' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'instructor' => 'required|string',
            'schedule' => 'required|string',
            'prelims' => 'required|numeric',
            'midterms' => 'required|numeric',
            'pre_finals' => 'required|numeric',
            'finals' => 'required|numeric',
            'date_taken' => 'required|date',
        ]);

        $data['student_id'] = $id;
        $data['average_grade'] = ($data['prelims'] + $data['midterms'] + $data['pre_finals'] + $data['finals']) / 4;
        $data['remarks'] = $data['average_grade'] >= 3.0 ? 'PASSED' : 'FAILED';

        $subject = Subject::create($data);

        return response()->json($subject, 201);
    }

    public function show($id, $subject_id)
    {
        $subject = Subject::where('student_id', $id)->findOrFail($subject_id);

        return response()->json($subject);
    }

    public function update(Request $request, $id, $subject_id)
    {
        $data = $request->validate([
            'subject_code' => 'string',
            'name' => 'string',
            'description' => 'string',
            'instructor' => 'string',
            'schedule' => 'string',
            'prelims' => 'numeric',
            'midterms' => 'numeric',
            'pre_finals' => 'numeric',
            'finals' => 'numeric',
            'date_taken' => 'date',
        ]);

        $subject = Subject::where('student_id', $id)->findOrFail($subject_id);
        $subject->update($data);

        if ($request->has(['prelims', 'midterms', 'pre_finals', 'finals'])) {
            $subject->average_grade = ($subject->prelims + $subject->midterms + $subject->pre_finals + $subject->finals) / 4;
            $subject->remarks = $subject->average_grade >= 3.0 ? 'PASSED' : 'FAILED';
            $subject->save();
        }

        return response()->json($subject);
    }
}
