<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = Report::where('province', 'LIKE', '%' . $request->PROVINCE . '%')->orderBy('created_at', 'desc')->with('responses', 'comments')->simplePaginate(2);

        return view('reports.report', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'description' => 'required|string|max:1000',
                'type'        => 'required|in:KEJAHATAN,PEMBANGUNAN,SOSIAL',
                'province'    => 'required|string|max:255',
                'regency'     => 'required|string|max:255',
                'subdistrict' => 'required|string|max:255',
                'village'     => 'required|string|max:255',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'statement'   => 'required|boolean',
            ],
            [
                'description.required' => 'Deskripsi pengaduan harus diisi.',
                'type.required'        => 'Jenis pengaduan harus dipilih.',
                'province.required'    => 'Provinsi harus diisi.',
                'regency.required'     => 'Kabupaten/Kota harus diisi.',
                'subdistrict.required' => 'Kecamatan harus diisi.',
                'village.required'     => 'Kelurahan/Desa harus diisi.',
                'image.image'          => 'File harus berupa gambar.',
                'image.mimes'          => 'Format gambar harus jpeg, png, atau jpg.',
                'image.max'            => 'Ukuran gambar maksimal 2MB.',
                'statement.required'   => 'Pernyataan harus diisi.',
                'statement.boolean'    => 'Pernyataan harus berupa nilai boolean (true atau false).',
            ]
        );

        $report = new Report();
        $report->user_id = Auth::id();
        $report->description = $validated['description'];
        $report->type = $validated['type'];
        $report->province = $validated['province'];
        $report->regency = $validated['regency'];
        $report->subdistrict = $validated['subdistrict'];
        $report->village = $validated['village'];
        $report->statement = $validated['statement'];
        $report->voting = 0;
        $report->viewers = 0;

        if ($request->hasFile('image')) {
            $report->image = $request->file('image')->store('reports', 'public');
        }

        $report->save();

        return redirect()->back()->with('success', 'Laporan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::with('comments')->find($id);

        $report->viewers += 1;
        $report->save();

        return view('reports.show', compact('report'));
    }


    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $report = Report::findOrFail($id);

        Comment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->route('report.show', $id)->with('success', 'Komentar berhasil ditambahkan.');
    }

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

        return redirect()->route('report.myReports')->with('success', 'Laporan berhasil dihapus.');
    }


    public function toggleVote($id)
    {
        $report = Report::findOrFail($id);

        // Ambil daftar laporan yang sudah divote dari sesi
        $votedReports = session('voted_reports', []);

        if (in_array($id, $votedReports)) {
            // Jika sudah divote, kurangi jumlah vote dan hapus dari sesi
            $report->voting -= 1;
            $report->save();

            // Hapus ID laporan dari array
            $votedReports = array_diff($votedReports, [$id]);
            session(['voted_reports' => $votedReports]);

            $message = 'Vote berhasil dihapus.';
        } else {
            // Jika belum divote, tambahkan jumlah vote dan simpan ke sesi
            $report->voting += 1;
            $report->save();

            // Tambahkan ID laporan ke array
            $votedReports[] = $id;
            session(['voted_reports' => $votedReports]);

            $message = 'Vote berhasil ditambahkan.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $fileName = 'report.' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ReportsExport($request), $fileName);
    }
}
