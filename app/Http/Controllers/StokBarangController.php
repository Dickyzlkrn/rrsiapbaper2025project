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

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'LIKE', '%' . $request->nama_barang . '%');
        }

        $stokBarangs = $query->orderBy('nama_barang')->get();

        // Jika user memilih rentang tanggal (biasanya per bulan)
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

            // Ambil bulan & tahun filter (berguna untuk stok awal)
            $bulanFilter = $tanggalAwal->month;
            $tahunFilter = $tanggalAwal->year;

            foreach ($stokBarangs as $barang) {
                // 1️⃣ Hitung total pemakaian SEBELUM bulan filter → untuk stok awal
                $terpakaiSebelum = PengajuanItem::where('nama_barang', $barang->nama_barang)
                    ->whereHas('pengajuan', function ($q) use ($tanggalAwal) {
                        $q->where('status', 'disetujui')
                            ->where('created_at', '<', $tanggalAwal);
                    })
                    ->sum('jumlah');

                // 2️⃣ Jumlah masuk SEBELUM bulan filter (jika kamu simpan tanggal update stok)
                $masukSebelum = $barang->where('id', $barang->id)
                    ->where('created_at', '<', $tanggalAwal)
                    ->sum('jumlah_masuk');

                // 3️⃣ Hitung stok awal bulan ini = stok_awal + semua masuk sebelumnya - semua terpakai sebelumnya
                $stokAwalBulanIni = ($barang->stok_awal + $masukSebelum) - $terpakaiSebelum;

                // 4️⃣ Hitung jumlah masuk DALAM bulan yang difilter
                $jumlahMasukBulanIni = $barang->where('id', $barang->id)
                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                    ->sum('jumlah_masuk');

                // 5️⃣ Hitung pemakaian DALAM bulan yang difilter
                $terpakaiBulanIni = PengajuanItem::where('nama_barang', $barang->nama_barang)
                    ->whereHas('pengajuan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                        $q->where('status', 'disetujui')
                            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                    })
                    ->sum('jumlah');

                // 6️⃣ Hitung stok akhir bulan ini
                $stokAkhirBulanIni = $stokAwalBulanIni + $jumlahMasukBulanIni - $terpakaiBulanIni;

                // 7️⃣ Set properti ke objek untuk dikirim ke view
                $barang->stok_awal_bulan = $stokAwalBulanIni;
                $barang->jumlah_masuk_bulan = $jumlahMasukBulanIni;
                $barang->terpakai = $terpakaiBulanIni;
                $barang->stok_akhir_dinamis = $stokAkhirBulanIni;
            }
        } else {
            // Jika tidak filter tanggal, tampilkan data default
            foreach ($stokBarangs as $barang) {
                $terpakaiTotal = PengajuanItem::where('nama_barang', $barang->nama_barang)
                    ->whereHas('pengajuan', function ($q) {
                        $q->where('status', 'disetujui');
                    })
                    ->sum('jumlah');

                $barang->stok_awal_bulan = $barang->stok_awal;
                $barang->jumlah_masuk_bulan = $barang->jumlah_masuk;
                $barang->terpakai = $terpakaiTotal;
                $barang->stok_akhir_dinamis = ($barang->stok_awal + $barang->jumlah_masuk) - $terpakaiTotal;
            }
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
            // Hitung stok akhir dinamis
            $terpakai = $this->getTotalPemakaian($item->nama_barang);
            $stokAkhir = ($item->stok_awal + $item->jumlah_masuk) - $terpakai;

            return [
                'label' => $item->nama_barang,
                'value' => $item->nama_barang,
                'satuan' => $item->satuan ?? '-',
                'stok_akhir' => $stokAkhir,
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
