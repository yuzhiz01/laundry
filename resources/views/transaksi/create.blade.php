@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('page_title', 'Entri Transaksi Kasir (POS)')
@section('page_subtitle', 'Pencatatan order cucian masuk dan kalkulasi biaya laundry')

@section('content')
<form id="transaksiForm" action="{{ route('transaksi.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Form Data & Item Selection -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Data Outlet & Pelanggan -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i class="fa-solid fa-store text-indigo-500"></i>
                        <span>Informasi Outlet & Pelanggan</span>
                    </h3>
                    <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">
                        {{ $suggestedInvoice }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Outlet Selector -->
                    <div>
                        <label for="id_outlet" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Outlet <span class="text-rose-500">*</span></label>
                        <select id="id_outlet" name="id_outlet" required onchange="loadOutletPackages(this.value)"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}" {{ $selectedOutletId == $outlet->id ? 'selected' : '' }}>{{ $outlet->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Batas Waktu Estimasi -->
                    <div>
                        <label for="batas_waktu" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Selesai (Batas Waktu) <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" id="batas_waktu" name="batas_waktu" 
                               value="{{ \Carbon\Carbon::now()->addDays(2)->format('Y-m-d\TH:i') }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Member Selection / Tab Option -->
                <div class="pt-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Data Pelanggan (Member) <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <button type="button" id="tabMemberExisting" onclick="switchMemberMode('existing')" class="text-xs px-2.5 py-1 rounded-lg font-semibold bg-indigo-600 text-white transition-all">
                                Pilih Member
                            </button>
                            <button type="button" id="tabMemberNew" onclick="switchMemberMode('new')" class="text-xs px-2.5 py-1 rounded-lg font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                                + Input Pelanggan Baru
                            </button>
                        </div>
                    </div>

                    <!-- Mode 1: Existing Member Dropdown -->
                    <div id="memberExistingContainer">
                        <select id="id_member" name="id_member" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Pelanggan Terdaftar --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->tlp }}) - {{ Str::limit($m->alamat, 30) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mode 2: New Member Form Inputs -->
                    <div id="memberNewContainer" class="hidden p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" id="new_member_nama" name="new_member_nama" placeholder="Nama Pelanggan Baru"
                                       class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">No. WhatsApp / Telepon</label>
                                <input type="text" id="new_member_tlp" name="new_member_tlp" placeholder="Contoh: 08123456789"
                                       class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Alamat</label>
                                <input type="text" id="new_member_alamat" name="new_member_alamat" placeholder="Alamat rumah / kos"
                                       class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Kelamin</label>
                                <select id="new_member_gender" name="new_member_gender" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Paket Laundry (Multi Item) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-indigo-500"></i>
                            <span>Daftar Item / Paket Cucian</span>
                        </h3>
                        <p class="text-xs text-slate-400">Tambahkan satu atau beberapa jenis paket cucian untuk transaksi ini</p>
                    </div>
                    <button type="button" onclick="addItemRow()" class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-bold transition-all">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Item</span>
                    </button>
                </div>

                <!-- Item Rows Container -->
                <div id="itemsContainer" class="space-y-3">
                    <!-- Dynamic Rows Will Be Injected Here -->
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Kalkulasi Pembayaran & Submit -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5 sticky top-6">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-calculator text-indigo-500"></i>
                    <span>Ringkasan Biaya</span>
                </h3>

                <!-- Subtotal Item -->
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Subtotal Item</span>
                        <span id="labelSubtotal" class="font-bold font-mono text-slate-900">Rp 0</span>
                    </div>

                    <!-- Diskon -->
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
                            <span>Diskon (%)</span>
                            <span id="labelDiskonVal" class="text-rose-600 font-semibold font-mono">- Rp 0</span>
                        </div>
                        <input type="number" id="diskon" name="diskon" value="0" min="0" max="100" step="1" oninput="calculateTotal()"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>

                    <!-- Pajak -->
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
                            <span>Pajak (Rp)</span>
                            <span id="labelPajakVal" class="text-slate-800 font-mono">+ Rp 0</span>
                        </div>
                        <input type="number" id="pajak" name="pajak" value="0" min="0" step="500" oninput="calculateTotal()"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>

                    <!-- Biaya Tambahan -->
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
                            <span>Biaya Tambahan (Antar/dll)</span>
                            <span id="labelBiayaTambahanVal" class="text-slate-800 font-mono">+ Rp 0</span>
                        </div>
                        <input type="number" id="biaya_tambahan" name="biaya_tambahan" value="0" min="0" step="500" oninput="calculateTotal()"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-slate-100 pt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold text-slate-900">Total Bayar</span>
                            <span id="labelGrandTotal" class="text-2xl font-black text-indigo-600 font-mono">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Pembayaran <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-500">
                            <input type="radio" name="dibayar" value="dibayar" class="text-emerald-600 focus:ring-0" checked>
                            <span class="text-emerald-700">Bayar Lunas</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all">
                            <input type="radio" name="dibayar" value="belum_dibayar" class="text-rose-600 focus:ring-0">
                            <span class="text-rose-700">Belum Bayar</span>
                        </label>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-check-circle text-base"></i>
                        <span>Simpan & Cetak Transaksi</span>
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="block text-center text-xs text-slate-500 hover:text-slate-800 font-semibold mt-3">
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // State of packages for current outlet
    let availablePakets = {!! json_encode($pakets) !!};
    let rowCounter = 0;

    function switchMemberMode(mode) {
        const tabExisting = document.getElementById('tabMemberExisting');
        const tabNew = document.getElementById('tabMemberNew');
        const containerExisting = document.getElementById('memberExistingContainer');
        const containerNew = document.getElementById('memberNewContainer');
        const idMemberSelect = document.getElementById('id_member');
        const newMemberNama = document.getElementById('new_member_nama');

        if (mode === 'new') {
            tabNew.className = 'text-xs px-2.5 py-1 rounded-lg font-semibold bg-indigo-600 text-white transition-all';
            tabExisting.className = 'text-xs px-2.5 py-1 rounded-lg font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all';
            containerExisting.classList.add('hidden');
            containerNew.classList.remove('hidden');
            idMemberSelect.value = '';
            idMemberSelect.removeAttribute('required');
            newMemberNama.setAttribute('required', 'required');
        } else {
            tabExisting.className = 'text-xs px-2.5 py-1 rounded-lg font-semibold bg-indigo-600 text-white transition-all';
            tabNew.className = 'text-xs px-2.5 py-1 rounded-lg font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all';
            containerNew.classList.add('hidden');
            containerExisting.classList.remove('hidden');
            newMemberNama.value = '';
            newMemberNama.removeAttribute('required');
            idMemberSelect.setAttribute('required', 'required');
        }
    }

    function loadOutletPackages(outletId) {
        fetch(`/transaksi/paket-by-outlet/${outletId}`)
            .then(res => res.json())
            .then(data => {
                availablePakets = data;
                // Re-render package options in all rows
                document.querySelectorAll('.paket-select').forEach(select => {
                    const currentVal = select.value;
                    let options = '<option value="">-- Pilih Paket Laundry --</option>';
                    availablePakets.forEach(p => {
                        options += `<option value="${p.id}" data-harga="${p.harga}" data-jenis="${p.jenis}">${p.nama_paket} (Rp ${Number(p.harga).toLocaleString('id-ID')})</option>`;
                    });
                    select.innerHTML = options;
                    select.value = currentVal;
                });
                calculateTotal();
            });
    }

    function addItemRow() {
        rowCounter++;
        const container = document.getElementById('itemsContainer');

        let paketOptions = '<option value="">-- Pilih Paket Laundry --</option>';
        availablePakets.forEach(p => {
            paketOptions += `<option value="${p.id}" data-harga="${p.harga}" data-jenis="${p.jenis}">${p.nama_paket} (Rp ${Number(p.harga).toLocaleString('id-ID')})</option>`;
        });

        const row = document.createElement('div');
        row.id = `item-row-${rowCounter}`;
        row.className = 'p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 transition-all animate-fade-in';
        row.innerHTML = `
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-slate-600">Item #${rowCounter}</span>
                <button type="button" onclick="removeItemRow(${rowCounter})" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-5">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Paket</label>
                    <select name="items[${rowCounter}][id_paket]" required onchange="onPaketChange(${rowCounter})"
                            class="paket-select w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                        ${paketOptions}
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Harga/Satuan</label>
                    <input type="text" id="price-display-${rowCounter}" readonly value="Rp 0"
                           class="w-full bg-slate-100 border border-slate-200 text-slate-600 rounded-xl px-3 py-2 text-xs font-mono font-medium">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah (Qty)</label>
                    <input type="number" name="items[${rowCounter}][qty]" id="qty-${rowCounter}" value="1" min="0.1" step="0.1" required oninput="calculateTotal()"
                           class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:border-indigo-500">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Subtotal Item</label>
                    <div id="subtotal-display-${rowCounter}" class="font-bold text-xs text-indigo-600 font-mono py-2">
                        Rp 0
                    </div>
                </div>
            </div>
            <div>
                <input type="text" name="items[${rowCounter}][keterangan]" placeholder="Catatan item (misal: warna putih, ada noda kecap, parfum vanilla...)"
                       class="w-full bg-white border border-slate-200 text-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
            </div>
        `;
        container.appendChild(row);
    }

    function removeItemRow(id) {
        const row = document.getElementById(`item-row-${id}`);
        if (row) {
            row.remove();
            calculateTotal();
        }
    }

    function onPaketChange(rowId) {
        const row = document.getElementById(`item-row-${rowId}`);
        const select = row.querySelector('.paket-select');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-harga') || 0;
        
        document.getElementById(`price-display-${rowId}`).value = 'Rp ' + Number(price).toLocaleString('id-ID');
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        const rows = document.querySelectorAll('#itemsContainer > div');

        rows.forEach(row => {
            const select = row.querySelector('.paket-select');
            const qtyInput = row.querySelector('input[type="number"]');
            const subtotalDisplay = row.querySelector('[id^="subtotal-display-"]');

            if (select && select.selectedIndex > 0 && qtyInput) {
                const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-harga')) || 0;
                const qty = parseFloat(qtyInput.value) || 0;
                const rowTotal = price * qty;
                subtotal += rowTotal;

                if (subtotalDisplay) {
                    subtotalDisplay.innerText = 'Rp ' + rowTotal.toLocaleString('id-ID');
                }
            } else if (subtotalDisplay) {
                subtotalDisplay.innerText = 'Rp 0';
            }
        });

        const diskonPercent = parseFloat(document.getElementById('diskon').value) || 0;
        const diskonVal = (diskonPercent / 100) * subtotal;
        const pajak = parseFloat(document.getElementById('pajak').value) || 0;
        const biayaTambahan = parseFloat(document.getElementById('biaya_tambahan').value) || 0;

        const grandTotal = Math.max(0, Math.round((subtotal - diskonVal) + pajak + biayaTambahan));

        document.getElementById('labelSubtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('labelDiskonVal').innerText = '- Rp ' + diskonVal.toLocaleString('id-ID');
        document.getElementById('labelPajakVal').innerText = '+ Rp ' + pajak.toLocaleString('id-ID');
        document.getElementById('labelBiayaTambahanVal').innerText = '+ Rp ' + biayaTambahan.toLocaleString('id-ID');
        document.getElementById('labelGrandTotal').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Initialize with 1 default row on load
    document.addEventListener('DOMContentLoaded', () => {
        addItemRow();
    });
</script>
@endpush
