<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar with unique filename
        $filename = 'avatars/' . Str::uuid() . '.' . $request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->storeAs('', $filename, 'public');

        // Save only the path, not the file itself
        $user->update(['avatar' => $filename]);

        return back()->with('success', 'Profile photo updated.');
    }
}