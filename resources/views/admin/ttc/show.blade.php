@extends('layouts.admin')

@section('judul', 'Booking '.$booking->booking_code)

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Booking {{ $booking->booking_code }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Tap Try and Collect, dibuat {{ tanggal_id($booking->created_at, true) }}.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.ttc.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ ucfirst($booking->status) }}</p>
            <p class="ad-angka__label mb-0">Status booking</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $booking->item->count() }}</p>
            <p class="ad-angka__label mb-0">Barang disiapkan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $booking->purchase_code ?: '-' }}</p>
            <p class="ad-angka__label mb-0">Kode pembelian kasir</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $booking->total_paid ? rupiah($booking->total_paid) : '-' }}</p>
            <p class="ad-angka__label mb-0">Total dibayar di toko</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Keterangan Booking</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <tbody>
            <tr>
                <th>Pelanggan</th>
                <td>
                    {{ $booking->pengguna?->name ?? '-' }}
                    @if($booking->pengguna)
                        <br><span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $booking->pengguna->email }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Toko</th>
                <td>{{ $booking->toko?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jadwal mencoba</th>
                <td>
                    @if($booking->slot)
                        {{ tanggal_id($booking->slot->date, true) }},
                        pukul {{ jam_singkat($booking->slot->start_time) }}–{{ jam_singkat($booking->slot->end_time) }}
                    @else
                        Jadwal sudah tidak ada.
                    @endif
                </td>
            </tr>
            <tr>
                <th>Diperiksa oleh</th>
                <td>{{ $booking->verified_by ?: '-' }}</td>
            </tr>
            <tr>
                <th>Selesai pada</th>
                <td>{{ $booking->completed_at ? tanggal_id($booking->completed_at, true) : '-' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Barang yang Ingin Dicoba</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Jumlah</th>
                <th>Jadi Dibeli</th>
            </tr>
            </thead>
            <tbody>
            @forelse($booking->item as $baris)
                <tr>
                    <td>{{ $baris->varian?->produk?->name ?? 'Produk sudah tidak ada' }}</td>
                    <td>{{ $baris->varian?->label() ?? '-' }}</td>
                    <td>{{ $baris->quantity }}</td>
                    <td>{{ $baris->purchased ? 'Ya' : 'Belum' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada barang pada booking ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Ubah Status Booking</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Pilih siap bila barang sudah disiapkan di toko. Status dibatalkan mengembalikan
        kuota slotnya supaya bisa dipakai pelanggan lain.
    </p>

    @if(in_array($booking->status, ['selesai', 'dibatalkan', 'kedaluwarsa']))
        <p class="cb-catatan cb-catatan--ingat mb-0">
            Booking ini sudah ditutup, statusnya tidak bisa diubah lagi.
        </p>
    @else
        <form class="cb-borang mt-2" action="{{ route('admin.ttc.status', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="cb-isian">
                <label class="cb-label" for="status">Status baru</label>
                <select class="cb-masukan" id="status" name="status" required style="max-width: 260px">
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected(old('status') === $pilihan)>{{ ucfirst($pilihan) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="cb-baris-isian cb-baris-isian--dua">
                <div class="cb-isian">
                    <label class="cb-label" for="purchase_code">Kode pembelian kasir</label>
                    <input class="cb-masukan" type="text" id="purchase_code" name="purchase_code" maxlength="40"
                           value="{{ old('purchase_code', $booking->purchase_code) }}">
                    <p class="cb-isian__bantu">Diisi bila kunjungan ditandai selesai.</p>
                </div>
                <div class="cb-isian">
                    <label class="cb-label" for="total_paid">Total dibayar di toko (Rp)</label>
                    <input class="cb-masukan" type="number" id="total_paid" name="total_paid" min="0" max="1000000000"
                           value="{{ old('total_paid', $booking->total_paid) }}">
                </div>
            </div>

            <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Status</button>
        </form>
    @endif
</div>

@endsection
