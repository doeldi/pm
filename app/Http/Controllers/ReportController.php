<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reports = Report::orderBy('created_at', 'desc')->with('responses')->get();

        // Kirim data laporan ke view
        return view('reports.report', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'type'        => 'required|in:KEJAHATAN,PEMBANGUNAN,SOSIAL',
            'province'    => 'required|string|max:255',
            'regency'     => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'village'     => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan laporan baru
        $report = new Report();
        $report->user_id = Auth::id(); // Ambil ID user yang sedang login
        $report->description = $validated['description'];
        $report->type = $validated['type'];
        $report->province = $validated['province'];
        $report->regency = $validated['regency'];
        $report->subdistrict = $validated['subdistrict'];
        $report->village = $validated['village'];
        $report->voting = 0;
        $report->viewers = 0;
        $report->statement;

        // Jika ada file gambar, simpan di storage dan simpan path-nya
        if ($request->hasFile('image')) {
            $report->image = $request->file('image')->store('reports', 'public');
        }

        $report->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::with('comments')->findOrFail($id); // Ambil laporan dengan komentar terkait

        $report->viewers += 1;
        $report->save();

        return view('reports.show', compact('report'));
    }

    /**
     * Menyimpan komentar pada pengaduan.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $report = Report::findOrFail($id);

        Comment::create([
            'report_id' => $report->id,
            'comment' => $request->comment,
            'user_id' => auth()->id(), // Mengambil ID pengguna yang login
        ]);

        return redirect()->route('report.show', $id)->with('success', 'Komentar berhasil ditambahkan.');
    }

    // app/Http/Controllers/ReportController.php
    public function myReports()
    {
        $user = auth()->user();
        $reports = Report::where('user_id', $user->id)->with('responses')->get();

        return view('reports.monitoring', compact('reports'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('report.data-report')->with('success', 'Laporan berhasil dihapus.');
    }

    public function vote($id)
    {
        // Cari data laporan berdasarkan ID
        $report = Report::findOrFail($id);

        // Tambahkan jumlah voting
        $report->voting += 1;
        $report->save();

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Vote berhasil ditambahkan.');
    }

    public function unvote($id)
    {
        // Cari data laporan berdasarkan ID
        $report = Report::findOrFail($id);

        // Kurangi jumlah voting
        $report->voting -= 1;
        $report->save();
        if ($report->voting < 0) {
            $report->voting = 0;
            $report->save();
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Vote berhasil dihapus.');
    }
}
