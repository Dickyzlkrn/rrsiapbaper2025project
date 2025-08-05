<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->get();
        return view('pages.tu.akun.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.tu.akun.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'signature' => 'nullable|image|mimes:png|max:2048', // validasi file tanda tangan
        ]);

        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'status' => 'approved',
            'signature' => $signaturePath,
        ]);

        return redirect()->route('tu.akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('pages.tu.akun.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:100|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:approved,submitted,rejected',
            'password' => 'nullable|string|min:6',
            'signature' => 'nullable|image|mimes:png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('signature')) {
            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }
            $user->signature = $request->file('signature')->store('signatures', 'public');
        }

        $user->save();

        return redirect()->route('tu.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->signature && Storage::disk('public')->exists($user->signature)) {
            Storage::disk('public')->delete($user->signature);
        }

        $user->delete();

        return redirect()->route('tu.akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
