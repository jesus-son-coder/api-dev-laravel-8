<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        // Validation
        $request->validate([
            "name" => "required",
            "description" => "required",
            "duration" => "required"
        ]);

        // Student Id + Create Data
        $student_id = auth()->user()->id;

        $project = new Project();

        $project->student_id = $student_id;
        $project->name = $request->name ;
        $project->description = $request->description ;
        $project->duration = $request->duration ;

        $project->save();

        // Send response
        return response()->json([
            "status" => 1,
            "message" => "Project created successfully"
        ],200);
    }

    public function listProject()
    {
        $student_id = auth()->user()->id;

        $projects = Project::where("student_id", $student_id)->get();

        // Send response
        return response()->json([
            "status" => 1,
            "message" => "List of Projects",
            "data" => $projects
        ],200);
    }

    public function singleProject($id)
    {
        $student_id = auth()->user()->id;

        // Tester si un project existe vraiment sous l'Id reçu en paramètre de la méthode "singleProject",
        // et Tester si l'ID du User authentifié (correspondant à celui de l'Access_Token fourni dans la Request) existe vraiment:
        if(Project::where([
            "id" => $id,
            "student_id" => $student_id
        ])->exists()) {

            $details = Project::where([
                "id" => $id,
                "student_id" => $student_id
            ])->first();

            return response()->json([
                "status" => 1,
                "message" => "Project detail",
                "data" => $details
            ],200);

        } else {
            return response()->json([
                "status" => 0,
                "message" => "Project not found"
            ],404);
        }
    }

    public function deleteProject($id)
    {
        $student_id = auth()->user()->id;

        if(Project::where([
            "id" => $id,
            "student_id" => $student_id
        ])) {
            $project = Project::where([
                "id" => $id,
                "student_id" => $student_id
            ])->first();

            $project->delete();

            return response()->json([
                "status" => 1,
                "message" => "Project has been deleted successfully"
            ], 404);
        }
        else {
            return response()->json([
                "status" => 0,
                "message" => "Project not found"
            ], 200);
        };
    }

}
