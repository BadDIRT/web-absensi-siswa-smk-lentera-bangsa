<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $jurusans = Jurusan::when(
            $request->search,
            fn($q, $s) =>
            $q->where('nama', 'like', "%{$s}%")->orWhere('kode', 'like', "%{$s}%")
        )->latest()->paginate(10);

        return view('admin.jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        return view('admin.jurusan.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:10|unique:jurusans,kode',
            'nama'        => 'required|string|max:100',
            'keterangan'  => 'nullable|string',
        ]);

        Jurusan::create($validated);

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.form', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:10|unique:jurusans,kode,' . $jurusan->id,
            'nama'        => 'required|string|max:100',
            'keterangan'  => 'nullable|string',
        ]);

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
