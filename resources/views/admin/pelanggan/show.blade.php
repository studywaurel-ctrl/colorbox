@extends('layouts.admin')

@section('judul', 'Pelanggan '.$pelanggan->name)

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">{{ $pelanggan->name }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                {{ $pelanggan->email }} · {{ $pelanggan->phone ?: 'tanpa nomor telepon' }} ·
                bergabung {{ tanggal_id($pelanggan->created_at) }}
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.pelanggan.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pelanggan->points }}</p>
            <p class="ad-angka__label mb-0">Poin sekarang</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pelanggan->member_level ?: '-' }}</p>
            <p class="ad-angka__label mb-0">Level anggota</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pesanan->count() }}</p>
            <p class="ad-angka__label mb-0">Pesanan terakhir tercatat</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $alamat->count() }}</p>
            <p class="ad-angka__label mb-0">Alamat tersimpan</p>
        </div>
    </div>

    <div class="ad-gulir mt-3">
        <table class="ad-tabel">
            <tbody>
            <tr><th>Tanggal lahir</th><td>{{ $pelanggan->birth_date ? tanggal_id($pelanggan->birth_date) : '-' }}</td></tr>
            <tr><th>Jenis kelamin</th><td>{{ $pelanggan->gender ?: '-' }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Alamat</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Label</th>
                <th>Penerima</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Utama</th>
            </tr>
            </thead>
            <tbody>
            @forelse($alamat as $baris)
                <tr>
                    <td>{{ $baris->label }}</td>
                    <td>{{ $baris->recipient }}</td>
                    <td>{{ $baris->phone }}</td>
                    <td>{{ $baris->detail }}, {{ $baris->district }}, {{ $baris->city }}, {{ $baris->province }} {{ $baris->postal_code }}</td>
                    <td>{{ $baris->is_primary ? 'Ya' : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada alamat tersimpan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Pesanan Terakhir</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($pesanan as $baris)
                <tr>
                    <td>{{ $baris->order_code }}</td>
                    <td>{{ tanggal_id($baris->created_at) }}</td>
                    <td>{{ $baris->method === 'ambil_toko' ? 'Ambil di toko' : 'Dikirim kurir' }}</td>
                    <td>{{ rupiah($baris->total) }}</td>
                    <td>{{ $baris->labelStatus() }}</td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.pesanan.show', $baris) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Pelanggan ini belum pernah memesan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Donasi</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Toko</th>
                <th>Diterima</th>
                <th>Poin</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($donasi as $baris)
                <tr>
                    <td>{{ $baris->donation_code }}</td>
                    <td>{{ $baris->toko?->name ?? '-' }}</td>
                    <td>{{ $baris->received_items !== null ? $baris->received_items.' potong' : '-' }}</td>
                    <td>{{ $baris->points_awarded ?: 0 }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $baris->status)) }}</td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.donasi.show', $baris) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada donasi dari pelanggan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Retur</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($retur as $baris)
                <tr>
                    <td>{{ $baris->return_code }}</td>
                    <td>{{ $baris->type === 'tukar' ? 'Tukar barang' : 'Pengembalian dana' }}</td>
                    <td>{{ tanggal_id($baris->created_at) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $baris->status)) }}</td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.retur.show', $baris) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada pengajuan retur dari pelanggan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Riwayat Poin</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Sumber</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($riwayatPoin as $baris)
                <tr>
                    <td>{{ tanggal_id($baris->created_at) }}</td>
                    <td>{{ $baris->type === 'masuk' ? 'Poin masuk' : 'Poin dipakai' }}</td>
                    <td>{{ $baris->source }}</td>
                    <td>{{ $baris->amount }}</td>
                    <td>{{ $baris->note ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada riwayat poin.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
