<?php

namespace App\Http\Controllers;

use App\Models\CustomOption;
use Illuminate\Http\Request;

class CustomOptionController extends Controller
{
    // Menampilkan daftar semua opsi custom buket
    public function index()
    {
        // Ambil data opsi custom, diurutkan berdasarkan kategori lalu nama
        $customOptions = CustomOption::orderBy('category')->orderBy('name')->get();
        return view('admin.custom_options.index', compact('customOptions'));
    }

    // Menampilkan halaman form tambah opsi custom baru
    public function create()
    {
        return view('admin.custom_options.create');
    }

    // Menyimpan data opsi custom baru ke database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'category' => 'required|string|max:50',
            'name'     => 'required|string|max:255',
            'price'    => 'nullable|numeric|min:0',
        ], [
            'category.required' => 'Kategori opsi wajib dipilih atau diisi.',
            'name.required'     => 'Nama pilihan opsi wajib diisi.',
            'price.min'         => 'Harga tambahan tidak boleh bernilai negatif.',
        ]);

        // Simpan data ke database (jika price kosong, otomatis isi 0)
        CustomOption::create([
            'category' => $request->category,
            'name'     => $request->name,
            'price'    => $request->price ?? 0,
        ]);

        // Kembali ke halaman index dengan notifikasi berhasil
        return redirect()->route('custom-options.index')
            ->with('success', 'Opsi custom "' . $request->name . '" berhasil ditambahkan!');
    }

    // Menampilkan form edit untuk opsi custom
    public function edit(CustomOption $customOption)
    {
        return view('admin.custom_options.edit', compact('customOption'));
    }

    // Memperbarui data opsi custom di database
    public function update(Request $request, CustomOption $customOption)
    {
        // Validasi input form
        $request->validate([
            'category' => 'required|string|max:50',
            'name'     => 'required|string|max:255',
            'price'    => 'nullable|numeric|min:0',
        ], [
            'category.required' => 'Kategori opsi wajib dipilih atau diisi.',
            'name.required'     => 'Nama pilihan opsi wajib diisi.',
            'price.min'         => 'Harga tambahan tidak boleh bernilai negatif.',
        ]);

        // Update data opsi custom
        $customOption->update([
            'category' => $request->category,
            'name'     => $request->name,
            'price'    => $request->price ?? 0,
        ]);

        // Kembali ke halaman index dengan notifikasi berhasil
        return redirect()->route('custom-options.index')
            ->with('success', 'Opsi custom berhasil diperbarui!');
    }

    // Menghapus data opsi custom dari database
    public function destroy(CustomOption $customOption)
    {
        $namaOpsi = $customOption->name;
        $customOption->delete();

        // Kembali ke halaman index dengan notifikasi berhasil
        return redirect()->route('custom-options.index')
            ->with('success', 'Opsi custom "' . $namaOpsi . '" berhasil dihapus!');
    }
}

