<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // POST
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        // Créer les Data du User + Enregistrer
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_no = $request->phone_no;

        $user->save();

        // Envoyer la Réponse
        return response()->json([
            "status" => 1,
            "message" => "User registered successfully"
        ],200);

    }

    // POST
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Verify User + Generate Token
        if(! $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {
            // Send Response
            return response()->json([
                "status" => 0,
                "message" => "Invalid credentials"
            ], 401);
        }
        else {
            // Send Response
            return response()->json([
                "status" => 1,
                "message" => "Logged in successfully",
                "access_token" => $token
            ],200);
        }
    }

    // GET
    public function profile()
    {
        // Obtenir les informations et data de l'Utilisateur connecté :
        $user_data = auth()->user();

        return response()->json([
            "status" => 1,
            "message" => "User profile data",
            "data" => $user_data
        ]);
    }

    // GET
    public function logout()
    {
        auth()->logout();

        return response()->json([
            "status" => 1,
            "message" => "User logged out"
        ]);
    }

}
