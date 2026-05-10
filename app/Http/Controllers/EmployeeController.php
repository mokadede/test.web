<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        // Ambil semua user kecuali yang punya role 'customer' (jika ada sistem customer di users)
        // Atau ambil semua jika semua user di tabel ini adalah staff/admin.
        // Berdasarkan migration, default adalah 'customer', jadi kita ambil yang staff/admin/owner.
        // Namun, user ingin "Karyawan", biasanya role-nya 'staff' atau 'admin'.
        return response()->json(User::where('role', '!=', 'customer')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'whatsapp' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff,owner',
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $employee
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|unique:users,email,' . $id,
            'whatsapp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|in:admin,staff,owner',
        ]);

        $data = $request->only(['name', 'email', 'whatsapp', 'role']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return response()->json([
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $employee
        ]);
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        
        // Jangan izinkan hapus diri sendiri (optional, tapi aman)
        if (auth()->id() == $id) {
            return response()->json(['message' => 'Tidak dapat menghapus akun sendiri'], 400);
        }

        $employee->delete();

        return response()->json(['message' => 'Karyawan berhasil dihapus']);
    }
}
