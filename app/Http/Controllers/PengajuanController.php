<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapPengajuanTUExport;
use Carbon\Carbon;
use App\Models\Barang;

class PengajuanController extends Controller
{
    public function indexUser(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $pengajuans = Pengajuan::with('items')
            ->where('user_id', Auth::id())
            ->whereDate('created_at', $tanggal)
            ->get();

        return view('pages.user.pengajuan.index', compact('pengajuans'));
    }

    public function refreshUserPengajuanTable(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $pengajuans = Pengajuan::with('items')
            ->where('user_id', Auth::id())
            ->whereDate('created_at', $tanggal)
            ->get();

        $tbody = view('pages.user.pengajuan.tbody', compact('pengajuans'))->render();

        return response()->json(['tbody' => $tbody]);
    }

    public function exportExcelTU()
    {
        return Excel::download(new RekapPengajuanTUExport(request()), 'rekap_pengajuan_tu.xlsx');
    }

    public function liveTable(Request $request)
    {
        $query = Pengajuan::with('items');

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.tu.daftar.daftar-permintaan', compact('pengajuans'));
    }

    public function createUser()
    {
        return view('pages.user.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengaju' => 'required|string|max:100',
            'ruangan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan_kecil' => 'nullable|string|max:255',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'nama_pengaju' => $request->nama_pengaju,
            'ruangan' => $request->ruangan,
            'keterangan' => $request->keterangan,
            'status' => 'diajukan',
        ]);

        foreach ($request->items as $item) {
            PengajuanItem::create([
                'pengajuan_id'    => $pengajuan->id,
                'nama_barang'     => $item['nama_barang'],
                'jumlah'          => $item['jumlah'],
                'keterangan_kecil'=> $item['keterangan_kecil'] ?? null,
            ]);
        }

        return redirect()->route('user.pengajuan')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);
        return view('pages.user.pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengaju' => 'required|string|max:100',
            'ruangan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan_kecil' => 'nullable|string|max:255',
        ]);

        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);

        $pengajuan->update([
            'nama_pengaju' => $request->nama_pengaju,
            'ruangan'      => $request->ruangan,
            'keterangan'   => $request->keterangan,
        ]);

        // refresh items (hapus lalu recreate dengan keterangan_kecil)
        $pengajuan->items()->delete();

        foreach ($request->items as $item) {
            PengajuanItem::create([
                'pengajuan_id'    => $pengajuan->id,
                'nama_barang'     => $item['nama_barang'],
                'jumlah'          => $item['jumlah'],
                'keterangan_kecil'=> $item['keterangan_kecil'] ?? null,
            ]);
        }

        return redirect()->route('user.pengajuan')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);
        $pengajuan->delete();

        return redirect()->route('user.pengajuan')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.user.pengajuan.show', compact('pengajuan'));
    }

    public function indexTU(Request $request)
    {
        $query = Pengajuan::with('items');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [
                $request->tanggal_awal,
                Carbon::parse($request->tanggal_akhir)->endOfDay()
            ]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        if ($request->ajax() || $request->ajax == 1) {
            $view = view('pages.tu.daftar.tbody', compact('pengajuans'))->render();

            return response()->json([
                'tbody' => $view,
                'pagination' => [
                    'current_page' => $pengajuans->currentPage(),
                    'last_page' => $pengajuans->lastPage(),
                    'prev_page_url' => $pengajuans->previousPageUrl(),
                    'next_page_url' => $pengajuans->nextPageUrl(),
                    'path' => $pengajuans->url(1),
                    'total' => $pengajuans->total(),
                ],
            ]);
        }

        $tahunList = Pengajuan::selectRaw('YEAR(created_at) as tahun')
            ->distinct()->orderByDesc('tahun')->pluck('tahun');

        $totalPengajuanHariIni = Pengajuan::whereDate('created_at', Carbon::today())->count();

        return view('pages.tu.daftar.index', compact(
            'pengajuans',
            'tahunList',
            'totalPengajuanHariIni'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,disetujui,ditolak,direvisi',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function generatePdf($id)
    {
        $pengajuan = Pengajuan::with(['items', 'user', 'approvedBy'])->findOrFail($id);

        $pdf = PDF::loadView('pages.user.pengajuan.pdf', [
            'pengajuan' => $pengajuan,
            'admin' => $pengajuan->approvedBy,
            'user' => $pengajuan->user
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('pengajuan-barang-' . $pengajuan->id . '.pdf');
    }

    public function destroyTU($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return redirect()->back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function editTU($id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);
        $stokList = \App\Models\StokBarang::pluck('stok_akhir', 'nama_barang')->toArray();

        return view('pages.tu.daftar.edit', compact('pengajuan', 'stokList'));
    }

    public function updateTU(Request $request, $id)
    {
        $request->validate([
            'nama_pengaju' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'status' => 'required|in:diajukan,disetujui,direvisi,ditolak',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan_kecil' => 'nullable|string|max:255',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        $statusLama = $pengajuan->status;
        $statusBaru = $request->status;

        $pengajuan->update([
            'nama_pengaju' => $request->nama_pengaju,
            'ruangan'      => $request->ruangan,
            'keterangan'   => $request->keterangan,
            'status'       => $statusBaru,
            'approved_by'  => $statusBaru === 'disetujui' ? Auth::id() : null,
        ]);

        $pengajuan->items()->delete();

        foreach ($request->items as $item) {
            PengajuanItem::create([
                'pengajuan_id'    => $pengajuan->id,
                'nama_barang'     => $item['nama_barang'],
                'jumlah'          => $item['jumlah'],
                'keterangan_kecil'=> $item['keterangan_kecil'] ?? null,
            ]);
        }

        if ($statusLama !== 'disetujui' && $statusBaru === 'disetujui') {
            foreach ($request->items as $item) {
                $stok = \App\Models\StokBarang::where('nama_barang', $item['nama_barang'])->first();

                if ($stok) {
                    if ($stok->stok_akhir >= $item['jumlah']) {
                        $stok->stok_akhir -= $item['jumlah'];
                        $stok->save();
                    } else {
                        return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $item['nama_barang']);
                    }
                }
            }
        }

        return redirect()->route('tu.daftar.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function dashboardTU()
    {
        return view('pages.tu.dashboard.dashboard', [
            'totalDiajukan' => Pengajuan::where('status', 'diajukan')->count(),
            'totalDisetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'totalDitolak' => Pengajuan::where('status', 'ditolak')->count(),
            'totalDirevisi' => Pengajuan::where('status', 'direvisi')->count(),
            'totalPengajuanHariIni' => Pengajuan::whereDate('created_at', Carbon::today())->count(),
        ]);
    }

    public function dashboardUser()
    {
        $userId = Auth::id();

        return view('pages.user.dashboard.dashboard', [
            'totalDiajukan' => Pengajuan::where('user_id', $userId)->where('status', 'diajukan')->count(),
            'totalDisetujui' => Pengajuan::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'totalDitolak' => Pengajuan::where('user_id', $userId)->where('status', 'ditolak')->count(),
            'totalDirevisi' => Pengajuan::where('user_id', $userId)->where('status', 'direvisi')->count(),
            'totalPengajuanHariIni' => Pengajuan::where('user_id', $userId)->whereDate('created_at', Carbon::today())->count(),
        ]);
    }
}
