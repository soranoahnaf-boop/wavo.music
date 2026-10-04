<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW — halaman profile (display)
    |--------------------------------------------------------------------------
    */

    public function show(): View
    {
        $user = Auth::user();

        $publicPlaylists = Playlist::withCount('songs')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('profile.show', compact('user', 'publicPlaylists'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT — form edit profile
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE — update info profile
    |--------------------------------------------------------------------------
    */

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Upload foto profile (opsional)
        if ($request->hasFile('profile_photo')) {

            $request->validate([
                'profile_photo' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            // Hapus foto lama
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            $user->profile_photo_path = $path;
        }

        // Update name & email
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


        public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $user = $request->user();
        $oldPath = $user->profile_photo_path;

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->forceFill(['profile_photo_path' => $path])->save();

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('public')->delete($oldPath);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'photo_url' => $user->profile_photo_url,
            ]);
        }

        return Redirect::route('profile.show')->with('success', 'Profile picture updated!');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY — hapus akun
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}