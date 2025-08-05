<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use Illuminate\Http\Request;
use App\Models\PengajuanItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokBarangExport;
use Carbon\Carbon;

class StokBarangController extends Controller
{


    public function exportExcel(Request $request)
    {
        return Excel::download(new StokBarangExport($request), 'rekap_stok_barang.xlsx');
    }
    /**
     * Menampilkan daftar stok barang dengan fitur filter nama barang
     */
    public function index(Request $request)
    {
        $query = StokBarang::query();

        // Filter nama barang
        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'LIKE', '%' . $request->nama_barang . '%');
        }

        $stokBarangs = $query->orderBy('nama_barang')->limit(1000)->get();

        // Hitung pemakaian dan stok akhir berdasarkan filter tanggal
        foreach ($stokBarangs as $barang) {
            // Terpakai semua waktu (untuk stok_akhir sistem)
            $terpakaiTotal = PengajuanItem::where('nama_barang', $barang->nama_barang)
                ->whereHas('pengajuan', function ($q) {
                    $q->where('status', 'disetujui');
                })
                ->sum('jumlah');

            // Terpakai berdasarkan filter tanggal
            $terpakaiQuery = PengajuanItem::where('nama_barang', $barang->nama_barang)
                ->whereHas('pengajuan', function ($q) {
                    $q->where('status', 'disetujui');
                });

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
                $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

                $terpakaiQuery->whereHas('pengajuan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                    $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                });
            }

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $terpakaiFilter = $terpakaiQuery->sum('jumlah');
                $barang->terpakai = $terpakaiFilter;
            } else {
                $barang->terpakai = 0; // default jika tidak difilter
            }

            $barang->stok_akhir_dinamis = ($barang->stok_awal + $barang->jumlah_masuk) - $terpakaiTotal;
        }
        return view('pages.tu.stok.index', compact('stokBarangs'));
    }



    /**
     * Menampilkan form tambah stok barang
     */
    public function create()
    {
        return view('pages.tu.stok.create');
    }

    /**
     * Menyimpan barang baru ke dalam stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|unique:stok_barangs,nama_barang',
            'satuan' => 'required|string|max:100', // ← tambahkan validasi satuan
            'stok_awal' => 'required|integer|min:0',
        ]);

        StokBarang::create([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan, // ← simpan satuan
            'stok_awal' => $request->stok_awal,
            'jumlah_masuk' => 0,
            'stok_akhir' => $request->stok_awal,
        ]);


        return redirect()->route('tu.stok.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk menambah jumlah barang masuk
     */
    public function edit($id)
    {
        $stok = StokBarang::findOrFail($id);

        $terpakai = PengajuanItem::where('nama_barang', $stok->nama_barang)
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->sum('jumlah');

        return view('pages.tu.stok.edit', compact('stok', 'terpakai'));
    }


    /**
     * Mengupdate stok barang (menambah jumlah masuk & hitung ulang stok akhir)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_awal' => 'required|integer|min:0',
            'jumlah_masuk' => 'required|integer|min:0',
            'tambah_jumlah_masuk' => 'nullable|integer|min:0',
        ]);

        $stok = StokBarang::findOrFail($id);

        $tambah = $request->tambah_jumlah_masuk ?? 0;
        $totalMasuk = $request->jumlah_masuk + $tambah;

        $terpakai = PengajuanItem::where('nama_barang', $stok->nama_barang)
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->sum('jumlah');

        $stok->stok_awal = $request->stok_awal;
        $stok->jumlah_masuk = $totalMasuk;
        $stok->stok_akhir = ($request->stok_awal + $totalMasuk) - $terpakai;

        $stok->save();

        return redirect()->route('tu.stok.index')->with('success', 'Stok barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = StokBarang::findOrFail($id);
        $stok->delete();

        return redirect()->route('tu.stok.index')->with('success', 'Stok barang berhasil dihapus.');
    }

    /**
     * Untuk fitur autocomplete nama barang (misalnya di form pengajuan)
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term');

        $stok = StokBarang::where('nama_barang', 'like', '%' . $term . '%')->get();

        $result = $stok->map(function ($item) {
            return [
                'label' => $item->nama_barang,
                'value' => $item->nama_barang,
                'satuan' => $item->satuan ?? '-', // ini tambahan penting
            ];
        });

        return response()->json($result);
    }

    private function getTotalPemakaian($nama_barang)
    {
        return PengajuanItem::where('nama_barang', $nama_barang)
            ->whereHas('pengajuan', function ($query) {
                $query->where('status', 'disetujui');
            })
            ->sum('jumlah');
    }
}
