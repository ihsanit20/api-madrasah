<?php

namespace App\Http\Controllers;

use App\Http\Resources\AcademicClassCollection;
use App\Http\Resources\AcademicClassResource;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionAcademicClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AcademicSession $academic_session)
    {
        // return 
        $academic_classes = $academic_session->academic_classes()
            ->with([
                'department_class:id,name'
            ])
            ->oldest('priority')
            ->get();

        return response([
            "academic_classes" => AcademicClassCollection::make($academic_classes),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicSession $academic_session, $academic_class_id)
    {
        $academic_class = $academic_session->academic_classes()
            ->with('author')
            ->where('id', $academic_class_id)
            ->first();

        // return $academic_class;

        return response([
            "academic_class" => $academic_class
                ? AcademicClassResource::make($academic_class)
                : (object) ([]),
        ], 200);
    }
}
