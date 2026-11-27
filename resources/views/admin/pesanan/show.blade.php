@extends('layouts.admin')

@section('judul', 'Pesanan '.$pesanan->order_code)

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Pesanan {{ $pesanan->order_code }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Dibuat {{ tanggal_id($pesanan->created_at, true) }}.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.pesanan.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pesanan->labelStatus() }}</p>
            <p class="ad-angka__label mb-0">Status pesanan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ rupiah($pesanan->total) }}</p>
            <p class="ad-angka__label mb-0">Total pesanan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pesanan->method === 'ambil_toko' ? 'Ambil di toko' : 'Dikirim kurir' }}</p>
            <p class="ad-angka__label mb-0">Metode penerimaan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $pesanan->item->count() }}</p>
            <p class="ad-angka__label mb-0">Jenis barang</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Pelanggan dan Pengiriman</h2>

    <div class="cb-kisi cb-kisi--dua mt-2">
        <div>
            <h3 style="font-size: var(--cb-h5)">Pelanggan</h3>
            <p class="mb-1">{{ $pesanan->pengguna?->name ?? '-' }}</p>
            <p class="mb-1" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                {{ $pesanan->pengguna?->email ?? '-' }}<br>
                {{ $pesanan->pengguna?->phone ?? 'Tanpa nomor telepon' }}
            </p>
            @if($pesanan->pengguna)
                <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.pelanggan.show', $pesanan->pengguna) }}">Lihat Pelanggan</a>
            @endif
        </div>
        <div>
            @if($pesanan->method === 'ambil_toko')
                <h3 style="font-size: var(--cb-h5)">Diambil di Toko</h3>
                <p class="mb-1">{{ $pesanan->toko?->name ?? 'Toko belum dipilih' }}</p>
                <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                    {{ $pesanan->toko?->address ?? '-' }}
                </p>
            @else
                <h3 style="font-size: var(--cb-h5)">Alamat Pengiriman</h3>
                @if($pesanan->alamat)
                    <p class="mb-1">{{ $pesanan->alamat->recipient }} ({{ $pesanan->alamat->phone }})</p>
                    <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                        {{ $pesanan->alamat->detail }},
                        {{ $pesanan->alamat->district }}, {{ $pesanan->alamat->city }},
                        {{ $pesanan->alamat->province }} {{ $pesanan->alamat->postal_code }}
                    </p>
                @else
                    <p class="mb-0">Alamat pengiriman tidak tercatat.</p>
                @endif
            @endif
        </div>
    </div>

    @if($pesanan->note)
        <p class="cb-catatan cb-catatan--ingat mt-3 mb-0">Catatan pelanggan: {{ $pesanan->note }}</p>
    @endif
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Barang yang Dipesan</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pesanan->item as $baris)
                <tr>
                    <td>{{ $baris->product_name }}</td>
                    <td>{{ $baris->color ?: '-' }}</td>
                    <td>{{ $baris->size ?: '-' }}</td>
                    <td>{{ rupiah($baris->price) }}</td>
                    <td>{{ $baris->quantity }}</td>
                    <td>{{ rupiah($baris->subtotal) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada barang pada pesanan ini.</td></tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <th colspan="5">Subtotal</th>
                <th>{{ rupiah($pesanan->subtotal) }}</th>
            </tr>
            <tr>
                <th colspan="5">Ongkos kirim</th>
                <th>{{ rupiah($pesanan->shipping_cost) }}</th>
            </tr>
            <tr>
                <th colspan="5">Potongan voucher</th>
                <th>{{ rupiah($pesanan->discount) }}</th>
            </tr>
            <tr>
                <th colspan="5">Poin dipakai</th>
                <th>{{ $pesanan->points_used }} poin</th>
            </tr>
            <tr>
                <th colspan="5">Total dibayar</th>
                <th>{{ rupiah($pesanan->total) }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Pembayaran</h2>

    @if($pesanan->pembayaran)
        <div class="ad-gulir mt-2">
            <table class="ad-tabel">
                <tbody>
                <tr>
                    <th>Status pembayaran</th>
                    <td>{{ ucfirst($pesanan->pembayaran->payment_status) }}</td>
                </tr>
                <tr>
                    <th>Status Midtrans</th>
                    <td>{{ $pesanan->pembayaran->transaction_status ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Cara bayar</th>
                    <td>{{ $pesanan->pembayaran->payment_method ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Nomor transaksi</th>
                    <td>{{ $pesanan->pembayaran->transaction_id ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Nilai tagihan</th>
                    <td>{{ rupiah($pesanan->pembayaran->gross_amount) }}</td>
                </tr>
                <tr>
                    <th>Dibayar pada</th>
                    <td>{{ $pesanan->pembayaran->paid_at ? tanggal_id($pesanan->pembayaran->paid_at, true) : 'Belum dibayar' }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <p class="mt-2 mb-2" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            Status pembayaran hanya berubah mengikuti jawaban Midtrans, tidak pernah diubah dengan tangan.
        </p>
        <form action="{{ route('admin.pembayaran.periksa', $pesanan->pembayaran) }}" method="POST">
            @csrf
            @method('PUT')
            <button class="cb-tombol cb-tombol--garis cb-tombol--kecil" type="submit">Periksa Status ke Midtrans</button>
        </form>
    @else
        <p class="mb-0">Pesanan ini belum punya catatan pembayaran.</p>
    @endif
</div>

@if($pesanan->pengiriman)
    <div class="ad-kartu">
        <h2 style="font-size: var(--cb-h4)">Pengiriman</h2>
        <div class="ad-gulir mt-2">
            <table class="ad-tabel">
                <tbody>
                <tr><th>Kurir</th><td>{{ $pesanan->pengiriman->courier ?: '-' }}</td></tr>
                <tr><th>Layanan</th><td>{{ $pesanan->pengiriman->service ?: '-' }}</td></tr>
                <tr><th>Nomor resi</th><td>{{ $pesanan->pengiriman->tracking_number ?: 'Belum ada' }}</td></tr>
                <tr><th>Dikirim pada</th><td>{{ $pesanan->pengiriman->shipped_at ? tanggal_id($pesanan->pengiriman->shipped_at, true) : '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Ubah Status Pesanan</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Perubahan status dicatat pada riwayat dan pelanggan langsung mendapat pemberitahuan.
        Nomor resi wajib diisi sebelum pesanan ditandai dikirim.
    </p>

    <form class="cb-borang mt-2" action="{{ route('admin.pesanan.status', $pesanan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="status">Status baru</label>
                <select class="cb-masukan" id="status" name="status" required>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected(old('status', $pesanan->status) === $pilihan)>{{ ucfirst(str_replace('_', ' ', $pilihan)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="keterangan">Keterangan untuk pelanggan</label>
                <input class="cb-masukan" type="text" id="keterangan" name="keterangan" maxlength="191"
                       value="{{ old('keterangan') }}" placeholder="Boleh dikosongkan">
                <p class="cb-isian__bantu">Bila kosong, keterangan bawaan yang dipakai.</p>
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="courier">Kurir</label>
                <input class="cb-masukan" type="text" id="courier" name="courier" maxlength="40"
                       value="{{ old('courier', $pesanan->pengiriman?->courier) }}" placeholder="JNE">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="service">Layanan kurir</label>
                <input class="cb-masukan" type="text" id="service" name="service" maxlength="60"
                       value="{{ old('service', $pesanan->pengiriman?->service) }}" placeholder="REG">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label" for="tracking_number">Nomor resi</label>
            <input class="cb-masukan" type="text" id="tracking_number" name="tracking_number" maxlength="60"
                   value="{{ old('tracking_number', $pesanan->pengiriman?->tracking_number) }}">
        </div>

        <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Status</button>
    </form>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Riwayat Status</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Waktu</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($riwayat as $langkah)
                <tr>
                    <td>{{ tanggal_id($langkah->happened_at, true) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $langkah->status)) }}</td>
                    <td>{{ $langkah->description }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Belum ada riwayat status.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
