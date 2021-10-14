<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{

    // POST
    public function courseEnrollment(Request $request)
    {
        // Validation
        $request->validate([
            "title" => "required",
            "description" => "required",
            "total_videos" => "required"
        ]);

        // Create Course Object
        $course = new Course();

        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        // Send Response
        return response()->json([
            "status" => 1,
            "message" => "Course enrolled successfully"
        ], 200);
    }

    // Fetch Total Courses where Users have been Enrolled.
    // Le Token JWT sera inclus dans le Headers de la Request,
    // afin d'identifier et autoriser le User à l'origine de la requête.
    // GET
    public function totalCourses()
    {
        $id = auth()->user()->id;

        $courses = User::find($id)->courses;

        // Send Response
        return response()->json([
            "status" => 1,
            "message" => "Total Courses enrolled",
            "data" => $courses
        ], 200);
    }

    // Le Token JWT sera inclus dans le Headers de la Request,
    // afin d'identifier et autoriser le User à l'origine de la requête.
    // DELETE
    public function deleteCourse($id)
    {
        $user_id = auth()->user()->id;
        if(Course::where([
            'id' => $id,
            'user_id' => $user_id
        ])->exists()) {
            $course = Course::find($id);
            $course->delete();

            return response()->json([
                "status" => 1,
                "message" => "Course deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Course not found"
            ], 404);
        }

    }


}
