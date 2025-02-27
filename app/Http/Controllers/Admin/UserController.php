<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('division')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'division_id' => 'nullable|exists:divisions,id',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'domicile' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Membuat Email Otomatis dari Name
        $firstName = strtolower(Str::before($validated['name'], ' '));
        $validated['email'] = $firstName . '@yourdomain.com';

        // Membuat Password Kuat secara Acak
        $randomPassword = Str::random(12); 
        $validated['password'] = Hash::make($randomPassword);

        // Menyimpan Profile Picture jika ada
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Menyimpan User
        $user = User::create($validated);

        // Mengembalikan Response dengan Password Asli
        return response()->json([
            'user' => $user,
            'generated_password' => $randomPassword // Password yang di-generate
        ], 201);
    }

    public function show($id)
    {
        $user = User::with('division')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'division_id' => 'nullable|exists:divisions,id',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'domicile' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Update Profile Picture jika ada
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
