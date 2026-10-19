<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user beserta role-nya.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hapus akun user. Admin tidak boleh menghapus akunnya sendiri.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $profileId = $user->profile_id;

        $user->delete();

        if ($profileId) {
            Profile::where('id', $profileId)->delete();
        }

        return redirect()->route('admin.user.index')->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
