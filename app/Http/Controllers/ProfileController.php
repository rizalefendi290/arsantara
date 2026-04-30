<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\AgentRequest;
use App\Models\Listing;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $listingQuery = Listing::where('user_id', $user->id);

        return view('profile.edit', [
            'user' => $user,
            'profileStats' => [
                'total_listings' => (clone $listingQuery)->count(),
                'active_listings' => (clone $listingQuery)->where('status', 'aktif')->count(),
                'pending_listings' => (clone $listingQuery)->where('status', 'pending')->count(),
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
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

    public function upgradeRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:agen,pemilik'
        ]);

        $user = Auth::user();

        // 🔥 cegah spam upgrade berulang
        if ($user->role != 'user') {
            return back()->with('error', 'Anda sudah memiliki role');
        }

        $user->update([
            'role' => $request->role,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil, menunggu verifikasi admin');
    }

    public function submitRequest(Request $request)
    {
        $user = Auth::user(); // 🔥 ini penting

        if (!$user) {
            return back()->with('error', 'User belum login');
        }
        
        $user->status = 'pending';

        // 🔥 simpan request role sementara
        $user->requested_role = $request->role;

        $user->save();

        return back()->with('success', 'Pengajuan berhasil, menunggu verifikasi');
    }
}
