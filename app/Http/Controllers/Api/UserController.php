<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function index()
    {
        $profile = UserProfile::all();
        return response()->json($profile);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|in:Admin,Client,Employee',
            'employee_id' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);        

        UserProfile::create([
            'user_id' => $user->id,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'role' => $request->role,
            'employee_id' => $request->employee_id,
            'company_id' => $request->company_id,
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function show($id)
    {
        $profile = UserProfile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json(['message' => 'User profile not found'], 404);
        }

        return response()->json($profile);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userProfile = UserProfile::where('user_id', $user->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|in:Admin,Client,Employee',
            'employee_id' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = false;

        foreach ($request->only(['first_name', 'last_name', 'phone', 'role', 'employee_id', 'company_id']) as $key => $value) {
            if ($userProfile->$key !== $value) {
                $userProfile->$key = $value;
                $updated = true;
            }
        }

        foreach ($request->only(['username', 'email', 'password']) as $key => $value) {
            if ($user->$key !== $value) {
                $user->$key = $value;
                $updated = true;
            }
        }

        if ($updated) {
            $user->save();
            $userProfile->save();

            Log::info('User updated: ', $user->toArray());

            $userId = auth()->check() ? auth()->user()->id : null;

            Activity::create([
                'user_id' => $userId,
                'action' => 'update',
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            Log::info('No changes detected.');
        }

        return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $userId = auth()->check() ? auth()->user()->id : null;

        Activity::create([
            'user_id' => $userId,
            'action' => 'delete',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

}

