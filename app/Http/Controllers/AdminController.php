<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->withCount('listings')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15)->appends($request->query());

        $stats = [
            'total' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'requests' => User::whereNotNull('requested_role')->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->role = $user->requested_role; // 🔥 pindahkan
        $user->status = 'approved';
        $user->requested_role = null;

        $user->save();

        return back()->with('success', 'User disetujui');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);

        $user->status = 'rejected';
        $user->requested_role = null;

        $user->save();

        return back()->with('error', 'User ditolak');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'admin', 'agen', 'pemilik'])],
            'status' => ['required', Rule::in(['normal', 'pending', 'approved', 'rejected'])],
            'requested_role' => ['nullable', Rule::in(['agen', 'pemilik'])],
        ]);

        if ($request->user()->id === $user->id) {
            $data['role'] = 'admin';
        }

        $user->update($data);

        return back()->with('success', 'Data user berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Admin tidak bisa menghapus akun sendiri');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu admin');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
