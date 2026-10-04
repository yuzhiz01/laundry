<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $gender = $request->query('gender');

        $members = Member::withCount('transaksi')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('tlp', 'like', "%{$search}%")
                             ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->when($gender, function ($query, $gender) {
                return $query->where('jenis_kelamin', $gender);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.index', compact('members', 'search', 'gender'));
    }

    public function create()
    {
        return view('member.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tlp' => 'required|string|max:15',
        ]);

        Member::create($validated);

        return redirect()->route('member.index')->with('success', 'Data pelanggan baru berhasil ditambahkan!');
    }

    public function edit(Member $member)
    {
        return view('member.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tlp' => 'required|string|max:15',
        ]);

        $member->update($validated);

        return redirect()->route('member.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Member $member)
    {
        if ($member->transaksi()->exists()) {
            return back()->withErrors(['error' => 'Pelanggan "' . $member->nama . '" tidak dapat dihapus karena memiliki riwayat ' . $member->transaksi()->count() . ' transaksi laundry.']);
        }

        try {
            $member->delete();
            return redirect()->route('member.index')->with('success', 'Data pelanggan "' . $member->nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus pelanggan karena masih terhubung dengan data transaksi.']);
        }
    }
}
