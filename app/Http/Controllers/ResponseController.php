<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Report;
use App\Models\ResponseProgress;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    /**
     * Display a listing of the responses.
     */
    public function index()
    {
        // Cuma tampilkan data yang sesuai dengan provinsi si staff
        $reports = Report::with('responses')
            ->where('province', Auth::user()->province)
            ->get();
        return view('response.index', compact('reports'));
    }

    /**
     * Show the form for creating a new response.
     */
    public function create()
    {
        // Ambil semua laporan untuk dropdown pilihan

    }

    /**
     * Store a newly created response in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'response_status' => 'required|in:REJECT,ON_PROCESS',
        ]);

        Response::create([
            'report_id' => $id,
            'staff_id' => Auth::user()->id,
            'response_status' => $request->response_status,
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    public function storeProgress(Request $request, string $id)
    {
        $request->validate([
            'histories' => 'required|string|max:1000',
        ]);

        ResponseProgress::create([
            'response_id' => $id,
            'histories' => $request->histories,
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    /**
     * Display the specified response.
     */
    public function show(string $id)
    {
        // Ambil response dengan semua progress-nya
        $response = Response::with('report', 'progress')->findOrFail($id);

        return view('response.show', compact('response'));
    }

    /**
     * Show the form for editing the specified response.
     */
    public function edit(string $id)
    {
        $response = Response::findOrFail($id);
        return view('response.edit', compact('response'));
    }

    /**
     * Update the specified response in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = Response::findOrFail($id);
        $response->update([
            'response_status' => $request->response_status,
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil diperbarui.');
    }

    /**
     * Remove the specified response from storage.
     */
    public function destroy(string $id)
    {
        $response = Response::findOrFail($id);

        // Periksa apakah tanggapan pernah tertaut dengan pengaduan
        if ($response->report->is_responded) {
            return redirect()->route('response.index')->with('error', 'Tanggapan tidak dapat dihapus karena sudah tertaut dengan laporan.');
        }

        $response->delete();
        return redirect()->route('response.index')->with('success', 'Tanggapan berhasil dihapus.');
    }
}
