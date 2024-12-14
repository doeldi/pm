<?php

// app/Http/Controllers/StaffController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar staff
    public function index()
    {
        $staffs = User::where('role', 'STAFF')->with('staffProvinces')->get();
        return view('hstaff.create', compact('staffs'));
    }

    // Menghapus staff jika belum pernah tertaut dengan tanggapan pengaduan
    public function destroy(User $user)
    {
        if ($user->responses()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus staff yang memiliki tanggapan.');
        }

        $user->delete();
        return redirect()->route('staff.index')->with('success', 'Staff berhasil dihapus.');
    }

    // Reset password menjadi 4 kata awal dari email
    public function resetPassword(User $user)
    {
        $emailParts = explode('@', $user->email);
        $newPassword = substr($emailParts[0], 0, 4) . '1234';
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->route('staff.index')->with('success', 'Password berhasil direset.');
    }

    // Menambahkan akun staff
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'province' => 'required|string'
        ]);

        $emailParts = explode('@', $request->email);
        $password = substr($emailParts[0], 0, 4) . '1234';

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'STAFF',
        ]);
        
        StaffProvince::create([
            'user_id' => $user->id,
            'province' => $request->province,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }
}
