<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function register(Request $request)
    {
        // Validation :
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:students",
            "password" => "required|confirmed"
        ]);

        // Create Data :
        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->phone_no = isset($request->phone_no) ? $request->phone_no : "";

        $student->save();

        // Send Response :
        return response()->json([
            "status" => 1,
            "message" => "Student registered successfully"
        ]);

    }

    public function login(Request $request)
    {
        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Check Student
        $student = Student::where("email", "=", $request->email)->first();

        if(isset($student->id)) {

            if(Hash::check($request->password, $student->password)) {
                // Create a Sanctum Token
                $token = $student->createToken("auth_token")->plainTextToken;

                // Send a response
                return response()->json([
                    "status" => 1,
                    "message" => "Student logged in successfully",
                    "access_token" => $token
                ],200);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "Password didn't match !"
                ],401);
            }

        } else {
            return response()->json([
                "status" => 0,
                "message" => "Student not found"
            ],404);
        }

    }

    public function profile()
    {
        return response()->json([
            'status' => 1,
            'message' => "Student Profile information",
            "data" => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' =>1,
            'message' => "Student logged out successfully"
        ]);
    }
}
