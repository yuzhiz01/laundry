<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $outletId = $request->query('outlet_id');

        $users = User::with('outlet')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($outletId, function ($query, $outletId) {
                return $query->where('id_outlet', $outletId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $outlets = Outlet::all();

        return view('user.index', compact('users', 'outlets', 'search', 'role', 'outletId'));
    }

    public function create()
    {
        $outlets = Outlet::all();
        return view('user.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:30|unique:tb_user,username',
            'password' => 'required|string|min:6',
            'id_outlet' => 'nullable|exists:tb_outlet,id',
            'role' => 'required|in:admin,kasir,owner',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'Pengguna baru berhasil didaftarkan!');
    }

    public function edit(User $user)
    {
        $outlets = Outlet::all();
        return view('user.edit', compact('user', 'outlets'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:30|unique:tb_user,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'id_outlet' => 'nullable|exists:tb_outlet,id',
            'role' => 'required|in:admin,kasir,owner',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan saat ini.']);
        }

        if ($user->transaksi()->exists()) {
            return back()->withErrors(['error' => 'Pengguna "' . $user->nama . '" tidak dapat dihapus karena tercatat sebagai kasir/petugas pada ' . $user->transaksi()->count() . ' data transaksi. Untuk menjaga riwayat audit keuangan, pengguna ini tidak boleh dihapus.']);
        }

        try {
            $user->delete();
            return redirect()->route('user.index')->with('success', 'Pengguna "' . $user->nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus pengguna karena masih terhubung dengan data lain di sistem.']);
        }
    }
}
