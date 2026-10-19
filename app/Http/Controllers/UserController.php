<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // 1. Tampilkan semua user
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // PERBAIKAN: Menggunakan auth()->id() bukan auth()->user()->id
        // Biar Admin gak bisa hapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
