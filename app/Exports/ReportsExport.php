<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $staffProvince = auth()->user()->staffProvinces->province;

        // Query untuk mendapatkan laporan sesuai filter
        $reports = Report::query()
            ->where('province', $staffProvince) // Filter berdasarkan provinsi staff
            ->when($this->request->filled('date'), function ($query) {
                // Filter berdasarkan tanggal jika `date` ada di request
                $query->whereDate('created_at', $this->request->input('date'));
            })
            ->get();

        // Mengembalikan hasil query
        return $reports;
    }

    public function headings(): array
    {
        return [
            '#',
            'Email Pelapor',
            'Dilaporkan Pada Tanggal',
            'Deskripsi Pengaduan',
            'URL Gambar',
            'Lokasi',
            'Jumlah Voting',
            'Status Pengaduan',
            'Progress Tanggapan',
            'Staff Terkait'
        ];
    }

    public function map($report): array
    {
        $response = $report->responses->first();

        $histories = $response ? $response->histories : collect();
        $staffId = $response ? $response->staff_id : null;

        return [
            $report->id,
            $report->user->email,
            $report->created_at->format('Y-m-d'),
            $report->description,
            $report->image,
            $report->village . ', ' . $report->subdistrict . ', ' . $report->regency . ', ' . $report->province,
            $report->voting,
            $response ? $response->response_status : '',
            $histories ? $histories->implode('<br>') : '',
            $staffId,
        ];
    }
}
