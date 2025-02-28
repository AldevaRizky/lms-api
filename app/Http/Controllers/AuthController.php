<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * [Auth] User Login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'errors' => 'Email or Password Wrong'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;
        $user->status = 'active';
        $user->save();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'token' => $token,
                'role' => $user->role,
                'status' => $user->status
            ]
        ]);
    }

    /**
     * [Auth] Update User Password.
     */
    public function update(Request $request, $userid)
    {
        $request->validate([
            'password' => 'required|string|max:100'
        ]);

        $user = User::find($userid);

        if (!$user) {
            return response()->json([
                'errors' => 'User is Not Found'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'fullName' => $user->name
            ]
        ]);
    }

    /**
     * [Auth] Get User Details.
     */
    public function show($userid)
    {
        $user = User::with('division')->find($userid);

        if (!$user) {
            return response()->json([
                'errors' => 'User is Not Found'
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'fullName' => $user->name,
                'department' => $user->division->name ?? null,
                'managerId' => $user->division->manager_id ?? null,
                'domisili' => $user->domicile,
                'phone' => $user->phone_number,
                'maxQuota' => $user->leaveBalances->sum('remaining_days')
            ]
        ]);
    }

    /**
     * [Auth] User Logout.
     */
    public function logout($userid)
    {
        $user = User::find($userid);

        if (!$user) {
            return response()->json([
                'errors' => 'Unauthorized'
            ], 401);
        }

        // Revoke all tokens
        $user->tokens()->delete();
        $user->status = 'inactive';
        $user->save();

        return response()->json([
            'data' => 'OK'
        ]);
    }
}
