<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function Users()
    {
        return User::all();
    }

    public function Login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Yanlış məlumat'], 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['Token' => $token]);
    }

    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'in:admin,user,editor'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user'
        ]);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

   public function changeRole(Request $request, $id)
{
    $request->validate([
        'role' => 'required|string|in:admin,editor,user',
    ]);

    $user = User::findOrFail($id);
    $user->role = $request->role;
    $user->save();

    return response()->json([
        'message' => 'Rol uğurla dəyişdirildi',
        'user' => $user
    ]);
}
}
