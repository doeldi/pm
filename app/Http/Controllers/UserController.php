<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffProvince;
use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // Menghitung jumlah laporan berdasarkan provinsi
        $userProvince = Auth::user()->staffProvinces->province;
        
        $reportsByProvince = Report::selectRaw('province, COUNT(*) as total_reports')
            ->where('province', 'LIKE', '%' . $userProvince . '%')
            ->groupBy('province')
            ->get();

        // Menghitung jumlah tanggapan berdasarkan provinsi
        $responsesByProvince = Response::selectRaw('reports.province, COUNT(responses.id) as total_responses')
            ->join('reports', 'responses.report_id', '=', 'reports.id')
            ->where('reports.province', 'LIKE', '%' . $userProvince . '%')
            ->groupBy('reports.province')
            ->get();

        // Data statistik lainnya
        $statistics = [
            'totalReports' => Report::where('province', 'LIKE', '%' . $userProvince . '%')->count(),
            'totalResponses' => Response::whereHas('report', function($query) use ($userProvince) {
                $query->where('province', 'LIKE', '%' . $userProvince . '%');
            })->count(),
        ];

        return view('hstaff.index', compact('statistics', 'reportsByProvince', 'responsesByProvince'));
    }


    public function create()
    {
        $user = Auth::user();
        $province = Auth::user()->staffProvinces->province;

        $staffs = User::where('role', 'STAFF')
            ->whereHas('staffProvinces', function($query) use ($province) {
                $query->where('province', $province);
            })
            ->with('staffProvinces')
            ->get();
            
        return view('hstaff.create', compact('staffs'));
    }

    // Menambahkan akun staff
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'province' => 'required|string'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'STAFF',
        ]);

        StaffProvince::create([
            'user_id' => $user->id,
            'province' => $request->province,
        ]);

        return redirect()->route('staff.create')->with('success', 'Staff berhasil ditambahkan.');
    }

    // Reset password menjadi 4 kata awal dari email
    public function resetPassword(User $user)
    {
        $emailParts = explode('@', $user->email);
        $newPassword = substr($emailParts[0], 0, 4) . '1234';
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->route('staff.index')->with('success', 'Password berhasil direset.');
    }

    // Menghapus staff jika belum pernah tertaut dengan tanggapan pengaduan
    public function destroy(User $user)
    {
        if (Response::where('staff_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus staff yang memiliki tanggapan.');
        }

        $user->delete();
        return redirect()->route('staff.create')->with('success', 'Staff berhasil dihapus.');
    }
}
