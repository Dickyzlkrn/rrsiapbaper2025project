<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pengajuan hari ini
        $totalPengajuanHariIni = Pengajuan::whereDate('created_at', Carbon::today())->count();

        // Total pengajuan bulan ini
        $totalPengajuanBulanIni = Pengajuan::whereYear('created_at', Carbon::now()->year)
                                           ->whereMonth('created_at', Carbon::now()->month)
                                           ->count();

        // Total pengajuan dengan status disetujui
        $totalDisetujui = Pengajuan::where('status', 'disetujui')->count();

        // Total pengajuan keseluruhan
        $totalPengajuan = Pengajuan::count();

        // Kirim data ke view dashboard
        return view('dashboard', compact(
            'totalPengajuanHariIni',
            'totalPengajuanBulanIni',
            'totalDisetujui',
            'totalPengajuan'
        ));
    }
}


