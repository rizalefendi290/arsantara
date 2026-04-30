<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpgradeController extends Controller
{
    public function submit(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'role' => $request->role,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil, menunggu approval admin');
    }
}
