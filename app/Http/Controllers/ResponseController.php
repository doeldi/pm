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
        // Ambil provinsi user login
        $user = Auth::user();
        $province = $user->staffProvinces->province ?? null;

        if (!$province) {
            return redirect()->back()->with('error', 'Provinsi Anda tidak ditemukan.');
        }

        // Ambil laporan dari provinsi user
        $reports = Report::with('responses', 'user')
            ->where('province', $province);

        // Sort by voting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'voting_asc':
                    $reports->orderBy('voting', 'asc');
                    break;
                case 'voting_desc':
                    $reports->orderBy('voting', 'desc');
                    break;
            }
        }

        $reports = $reports->get();

        return view('response.index', compact('reports'));
    }

    /**
     * Show the form for creating a new response.
     */
    public function create()
    {
        // 
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
        $response = Response::with('report', 'progress')->findOrFail($id);

        return view('response.show', compact('response'));
    }

    /**
     * Show the form for editing the specified response.
     */
    public function edit(string $id)
    {
        //
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
    /**
     * Remove the specified progress from storage.
     */
    public function destroy(string $id)
    {
        $progress = ResponseProgress::findOrFail($id);
        $progress->delete();

        return redirect()->back()->with('success', 'Progress berhasil dihapus.');
    }
}
