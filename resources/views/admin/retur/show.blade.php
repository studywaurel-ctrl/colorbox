@extends('layouts.admin')

@section('judul', 'Retur '.$retur->return_code)

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Retur {{ $retur->return_code }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Diajukan {{ tanggal_id($retur->created_at, true) }}.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.retur.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ ucfirst(str_replace('_', ' ', $retur->status)) }}</p>
            <p class="ad-angka__label mb-0">Status pengajuan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $retur->type === 'tukar' ? 'Tukar barang' : 'Pengembalian dana' }}</p>
            <p class="ad-angka__label mb-0">Jenis pengajuan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $retur->method === 'toko' ? 'Dibawa ke toko' : 'Dikirim' }}</p>
            <p class="ad-angka__label mb-0">Cara pengembalian</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $retur->item->count() }}</p>
            <p class="ad-angka__label mb-0">Jenis barang diretur</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Keterangan Pengajuan</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <tbody>
            <tr>
                <th>Pelanggan</th>
                <td>
                    {{ $retur->pengguna?->name ?? '-' }}
                    @if($retur->pengguna)
                        <br><span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $retur->pengguna->email }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Pesanan</th>
                <td>
                    @if($retur->pesanan)
                        <a href="{{ route('admin.pesanan.show', $retur->pesanan) }}">{{ $retur->pesanan->order_code }}</a>
                    @else
                        {{ $retur->purchase_code ?: 'Pembelian di toko tanpa kode' }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Toko</th>
                <td>{{ $retur->toko?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alasan pelanggan</th>
                <td>{{ $retur->reason }}</td>
            </tr>
            <tr>
                <th>Catatan admin</th>
                <td>{{ $retur->admin_note ?: 'Belum ada catatan.' }}</td>
            </tr>
            <tr>
                <th>Disetujui pada</th>
                <td>{{ $retur->approved_at ? tanggal_id($retur->approved_at, true) : '-' }}</td>
            </tr>
            <tr>
                <th>Selesai pada</th>
                <td>{{ $retur->completed_at ? tanggal_id($retur->completed_at, true) : '-' }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    @if($retur->photo)
        <div class="mt-3" style="max-width: 320px">
            <p class="cb-label">Foto barang dari pelanggan</p>
            <img src="{{ gambar_produk($retur->photo) }}" alt="Foto barang yang diretur" style="width: 100%">
        </div>
    @endif
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Barang yang Diretur</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($retur->item as $baris)
                <tr>
                    <td>{{ $baris->product_name }}</td>
                    <td>{{ $baris->varian?->label() ?? '-' }}</td>
                    <td>{{ $baris->quantity }}</td>
                    <td>{{ $baris->note ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada barang yang dicatat pada pengajuan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Ubah Status Retur</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Bila pengajuan ditolak, alasannya wajib ditulis pada catatan admin.
        Untuk retur jenis tukar, stok barang dikembalikan ketika status menjadi selesai.
    </p>

    @if(in_array($retur->status, ['selesai', 'dibatalkan']))
        <p class="cb-catatan cb-catatan--ingat mb-0">
            Pengajuan ini sudah ditutup, statusnya tidak bisa diubah lagi.
        </p>
    @else
        <form class="cb-borang mt-2" action="{{ route('admin.retur.status', $retur) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="cb-baris-isian cb-baris-isian--dua">
                <div class="cb-isian">
                    <label class="cb-label" for="status">Status baru</label>
                    <select class="cb-masukan" id="status" name="status" required>
                        @foreach($daftarStatus as $pilihan)
                            <option value="{{ $pilihan }}" @selected(old('status') === $pilihan)>{{ ucfirst($pilihan) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cb-isian">
                    <label class="cb-label" for="admin_note">Catatan admin</label>
                    <input class="cb-masukan" type="text" id="admin_note" name="admin_note" maxlength="191"
                           value="{{ old('admin_note', $retur->admin_note) }}">
                    <p class="cb-isian__bantu">Catatan ini ikut dikirim ke pemberitahuan pelanggan.</p>
                </div>
            </div>

            <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Status</button>
        </form>
    @endif
</div>

@endsection
