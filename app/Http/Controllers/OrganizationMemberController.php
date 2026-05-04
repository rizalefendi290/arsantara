<?php

namespace App\Http\Controllers;

use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationMemberController extends Controller
{
    public function index()
    {
        $members = OrganizationMember::orderBy('sort_order')
            ->latest()
            ->get();

        return view('admin.organization.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('organization', 'public');
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        OrganizationMember::create($data);

        return back()->with('success', 'Data struktur organisasi berhasil ditambahkan');
    }

    public function update(Request $request, OrganizationMember $organization)
    {
        $data = $this->validatedData($request, false);

        if ($request->hasFile('photo')) {
            if ($organization->photo) {
                Storage::disk('public')->delete($organization->photo);
            }

            $data['photo'] = $request->file('photo')->store('organization', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $organization->update($data);

        return back()->with('success', 'Data struktur organisasi berhasil diperbarui');
    }

    public function destroy(OrganizationMember $organization)
    {
        if ($organization->photo) {
            Storage::disk('public')->delete($organization->photo);
        }

        $organization->delete();

        return back()->with('success', 'Data struktur organisasi berhasil dihapus');
    }

    private function validatedData(Request $request, bool $photoRequired = true): array
    {
        return $request->validate([
            'photo' => [$photoRequired ? 'required' : 'nullable', 'image', 'max:4096'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'profile' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
