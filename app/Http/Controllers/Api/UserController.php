<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        return new DataResource(true, 'List Data Users', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = ValidationHelper::validateDataUser($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'message' => $errors
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ], 201);
        return new DataResource(true, 'Register Successfully', $user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:7'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return new DataResource(true, 'Detail Data User', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return new DataResource(true, 'Update Successfully', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function refreshToken(Request $request)
    {
        $user = Auth::user();
        $token = $user->createToken('refreshToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Refresh Token Successfully',
            'token' => $token,
        ]);
    }
}
