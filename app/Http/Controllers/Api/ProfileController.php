<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Handle avatar removal (user wants to clear their photo)
        if ($request->boolean('remove_avatar')) {
            if ($user->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['image'] = $path;
        }

        $user->update($data);

        return $this->successResponse('Profile updated successfully', new UserResource($user->fresh()));
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return $this->errorResponse('Current password is incorrect', [], 422);
        }

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return $this->successResponse('Password changed');
    }
}
