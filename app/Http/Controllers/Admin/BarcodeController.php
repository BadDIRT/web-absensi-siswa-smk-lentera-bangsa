<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    /**
     * GET /admin/barcode
     */
    public function index(Request $request)
    {
        $siswas = Siswa::with('kelas.jurusan')
            ->where('status', 'aktif')
            ->when($request->kelas_id, fn($q, $id) => $q->where('kelas_id', $id))
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->where('nama', 'like', "%{$s}%")
                    ->orWhere('nis', 'like', "%{$s}%")
                    ->orWhere('nipd', 'like', "%{$s}%")
            )
            ->when(
                $request->filter === 'no_barcode',
                fn($q) =>
                $q->whereNull('no_barcode')->whereNotNull('nipd')->where('nipd', '!=', '')
            )
            ->when(
                $request->filter === 'has_barcode',
                fn($q) =>
                $q->whereNotNull('no_barcode')->where('no_barcode', '!=', '')
            )
            ->orderBy('kelas_id')
            ->orderBy('nama')
            ->paginate(20);

        $kelases = Kelas::with('jurusan')->orderBy('nama')->get();

        $belumPunyaBarcode = Siswa::where('status', 'aktif')
            ->whereNotNull('nipd')
            ->where('nipd', '!=', '')
            ->whereNull('no_barcode')
            ->count();

        // Inisialisasi Barcode Generator
        $generator = new BarcodeGeneratorPNG();

        return view('admin.barcode.index', compact('siswas', 'kelases', 'belumPunyaBarcode', 'generator'));
    }

    /**
     * POST /admin/barcode/generate-all
     */
    public function generateAll(Request $request)
    {
        $siswas = Siswa::where('status', 'aktif')
            ->whereNotNull('nipd')
            ->where('nipd', '!=', '')
            ->whereNull('no_barcode')
            ->get();

        if ($siswas->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa yang perlu digenerate kode-nya.');
        }

        $count = 0;
        foreach ($siswas as $siswa) {
            $siswa->update(['no_barcode' => Siswa::generateBarcode($siswa->nipd)]);
            $count++;
        }

        return back()->with('success', "{$count} kode berhasil digenerate.");
    }

    /**
     * POST /admin/barcode/{siswa}/generate
     */
    public function generateSingle(Siswa $siswa)
    {
        if (empty($siswa->nipd)) {
            return back()->with('error', "Siswa {$siswa->nama} belum memiliki NIPD.");
        }
        if (!empty($siswa->no_barcode)) {
            return back()->with('error', "Siswa {$siswa->nama} sudah memiliki kode.");
        }

        $siswa->update([
            'no_barcode' => Siswa::generateBarcode($siswa->nipd),
        ]);

        return back()->with('success', "Kode berhasil digenerate untuk {$siswa->nama}.");
    }

    /**
     * GET /admin/barcode/print
     */
    public function print(Request $request)
    {
        $siswas = Siswa::with('kelas.jurusan')
            ->where('status', 'aktif')
            ->whereNotNull('no_barcode')
            ->where('no_barcode', '!=', '')
            ->when($request->kelas_id, fn($q, $id) => $q->where('kelas_id', $id))
            ->orderBy('kelas_id')
            ->orderBy('nama')
            ->get();

        if ($siswas->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa dengan kode untuk dicetak.');
        }

        $title = $request->kelas_id
            ? 'Kartu Absensi — ' . $siswas->first()->kelas->nama
            : 'Kartu Absensi Semua Siswa Aktif';

        $generator = new BarcodeGeneratorPNG();

        return view('admin.barcode.print', compact('siswas', 'title', 'generator'));
    }
}
