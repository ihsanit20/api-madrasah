<?php

namespace App\Http\Controllers;

use App\Http\Resources\AcademicSessionCollection;
use App\Http\Resources\AcademicSessionResource;
use App\Models\AcademicSession;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentAcademicSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Department $department)
    {
        AcademicSessionCollection::wrap('academic_sessions');

        return AcademicSessionCollection::make(
            $department->academic_sessions()
                ->paginate(request()->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department)
    {
        // return $this->getValidatedDate($department->id, $request);

        $academic_session = $department->academic_sessions()
            ->create(
                $this->getValidatedDate($department->id, $request)
            );

        return response([
            "academic_session" => AcademicSessionResource::make($academic_session),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department, $academic_session_id)
    {
        $academic_session = $department->academic_sessions()
            ->where('id', $academic_session_id)
            ->first();

        // return $academic_session;

        return response([
            "academic_session" => AcademicSessionResource::make($academic_session),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department, $academic_session_id)
    {
        // return $this->getValidatedDate($department->id, $request, $academic_session->id);

        // return
        $academic_session = $department->academic_sessions()
            ->where('id', $academic_session_id)
            ->first();

        if($academic_session) {
            $academic_session->update(
                $this->getValidatedDate($department->id, $request, $academic_session->id)
            );
        }

        return response([
            "academic_session" => AcademicSessionResource::make($academic_session),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, $academic_session_id)
    {
        $academic_session = $department->academic_sessions()
            ->where('id', $academic_session_id)
            ->first();

        if($academic_session) {
            $academic_session->delete();
        }

        return response([
            "message" => "success",
        ], 200);
    }

    protected function getValidatedDate($department_id, $request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                Rule::unique(AcademicSession::class, 'name')
                    ->where("department_id", $department_id)
                    ->ignore($id)
            ],
            'starting' => [],
            'ending' => [],
            'is_active' => [
                'sometimes',
                'required',
                'boolean',
            ]
        ]);
    }
}
