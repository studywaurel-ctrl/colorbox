@extends('layouts.app')

@section('judul', 'Verifikasi Staf Toko')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <h1>Verifikasi Staf Toko</h1>
        <p class="cb-catatan cb-catatan--ingat" style="max-width: 70ch">
            Halaman ini dibuka oleh <strong>staf toko</strong> setelah memindai QR pada layar pelanggan,
            bukan halaman untuk pelanggan. Bila Anda pelanggan, cukup tunjukkan QR ini kepada staf toko.
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-6">
            <section class="cb-panel">
                <h2 style="font-size: var(--cb-h4)">Rincian singkat</h2>

                @if($jenis === 'donasi')
                    <ul class="cb-rincian-biaya">
                        <li><span>Kode donasi</span><strong>{{ $data->donation_code }}</strong></li>
                        <li><span>Nama pelanggan</span><strong>{{ $data->pengguna ? $data->pengguna->name : '-' }}</strong></li>
                        <li><span>Toko</span><strong>{{ $data->toko ? $data->toko->name : '-' }}</strong></li>
                        <li><span>Jadwal</span><strong>
                            @if($data->slot)
                                {{ tanggal_id($data->slot->date, true) }},
                                {{ jam_singkat($data->slot->start_time) }} - {{ jam_singkat($data->slot->end_time) }}
                            @else
                                -
                            @endif
                        </strong></li>
                        <li><span>Perkiraan jumlah</span><strong>{{ $data->estimated_items }} potong</strong></li>
                        <li><span>Catatan kondisi</span><strong>{{ $data->condition_note ?: '-' }}</strong></li>
                        <li><span>Status sekarang</span><strong>{{ ucfirst(str_replace('_', ' ', $data->status)) }}</strong></li>
                    </ul>
                @elseif($jenis === 'ttc')
                    <ul class="cb-rincian-biaya">
                        <li><span>Kode booking</span><strong>{{ $data->booking_code }}</strong></li>
                        <li><span>Nama pelanggan</span><strong>{{ $data->pengguna ? $data->pengguna->name : '-' }}</strong></li>
                        <li><span>Toko</span><strong>{{ $data->toko ? $data->toko->name : '-' }}</strong></li>
                        <li><span>Jadwal</span><strong>
                            @if($data->slot)
                                {{ tanggal_id($data->slot->date, true) }},
                                {{ jam_singkat($data->slot->start_time) }} - {{ jam_singkat($data->slot->end_time) }}
                            @else
                                -
                            @endif
                        </strong></li>
                        <li><span>Status sekarang</span><strong>{{ ucfirst(str_replace('_', ' ', $data->status)) }}</strong></li>
                    </ul>

                    <h3 class="mt-4" style="font-size: var(--cb-h4)">Produk yang dicoba</h3>
                    <ul style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut); padding-left: 1.1rem">
                        @foreach($data->item as $item)
                            <li>
                                {{ $item->varian && $item->varian->produk ? $item->varian->produk->name : 'Produk' }}
                                — {{ $item->varian ? $item->varian->label() : '-' }}
                                ({{ $item->varian && $item->varian->produk ? rupiah($item->varian->produk->hargaJual()) : '-' }})
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="cb-rincian-biaya">
                        <li><span>Kode retur</span><strong>{{ $data->return_code }}</strong></li>
                        <li><span>Nama pelanggan</span><strong>{{ $data->pengguna ? $data->pengguna->name : '-' }}</strong></li>
                        <li><span>Toko</span><strong>{{ $data->toko ? $data->toko->name : '-' }}</strong></li>
                        <li><span>Jenis</span><strong>{{ ucfirst($data->type) }}</strong></li>
                        <li><span>Cara</span><strong>{{ ucfirst($data->method) }}</strong></li>
                        <li><span>Alasan</span><strong>{{ $data->reason ?: '-' }}</strong></li>
                        <li><span>Status sekarang</span><strong>{{ ucfirst(str_replace('_', ' ', $data->status)) }}</strong></li>
                    </ul>

                    <h3 class="mt-4" style="font-size: var(--cb-h4)">Barang yang diretur</h3>
                    <ul style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut); padding-left: 1.1rem">
                        @forelse($data->item as $item)
                            <li>{{ $item->product_name }} — {{ $item->quantity }} potong</li>
                        @empty
                            <li>Tidak ada rincian barang.</li>
                        @endforelse
                    </ul>
                @endif
            </section>
        </div>

        <div class="col-lg-6">
            <form action="{{ route('staf.verifikasi.simpan', ['jenis' => $jenis, 'kode' => $kode]) }}"
                  method="POST" class="cb-borang cb-panel">
                @csrf

                @if($jenis === 'donasi')
                    <h2 style="font-size: var(--cb-h4)">Hasil pemeriksaan donasi</h2>
                    <div class="cb-isian">
                        <label class="cb-label" for="jumlah-diterima">Jumlah pakaian yang diterima (potong)</label>
                        <input class="cb-masukan" type="number" id="jumlah-diterima" name="jumlah_diterima"
                               value="{{ old('jumlah_diterima', $data->estimated_items) }}" min="0" max="200">
                        <span class="cb-isian__bantu">
                            Isi 0 bila tidak ada pakaian yang bisa diterima. Setiap potong bernilai {{ $poinPerPotong }} poin.
                        </span>
                    </div>
                @elseif($jenis === 'ttc')
                    <h2 style="font-size: var(--cb-h4)">Produk yang jadi dibeli</h2>
                    <div class="cb-isian">
                        <span class="cb-label">Centang produk yang dibawa pulang pelanggan</span>
                        <div class="cb-pilihan__daftar">
                            @foreach($data->item as $item)
                                <label class="cb-pilih">
                                    <input type="checkbox" name="dibeli[]" value="{{ $item->id }}"
                                           @if($item->purchased) checked @endif>
                                    <span>
                                        {{ $item->varian && $item->varian->produk ? $item->varian->produk->name : 'Produk' }}
                                        ({{ $item->varian ? $item->varian->label() : '-' }})
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <span class="cb-isian__bantu">
                            Bila tidak ada yang dicentang, kunjungan tetap ditandai selesai tanpa pembelian.
                        </span>
                    </div>
                @else
                    <h2 style="font-size: var(--cb-h4)">Keputusan retur</h2>
                    <div class="cb-isian">
                        <span class="cb-label">Keputusan</span>
                        <div class="cb-pilihan__daftar">
                            <label class="cb-pilih">
                                <input type="radio" name="keputusan" value="diproses" checked>
                                <span>Diterima, lanjut diproses</span>
                            </label>
                            <label class="cb-pilih">
                                <input type="radio" name="keputusan" value="ditolak">
                                <span>Ditolak</span>
                            </label>
                        </div>
                    </div>
                    <div class="cb-isian">
                        <label class="cb-label" for="catatan-retur">Catatan pemeriksaan</label>
                        <input class="cb-masukan" type="text" id="catatan-retur" name="catatan"
                               value="{{ old('catatan') }}" maxlength="150"
                               placeholder="Misalnya: label masih utuh, tidak ada bekas pakai">
                    </div>
                @endif

                <div class="cb-baris-isian cb-baris-isian--dua">
                    <div class="cb-isian">
                        <label class="cb-label" for="nama-staf">Nama staf toko</label>
                        <input class="cb-masukan" type="text" id="nama-staf" name="nama_staf"
                               value="{{ old('nama_staf') }}" maxlength="100" required>
                    </div>
                    <div class="cb-isian">
                        <label class="cb-label" for="pin-toko">PIN toko</label>
                        <input class="cb-masukan" type="password" id="pin-toko" name="pin"
                               autocomplete="off" maxlength="20" required>
                        <span class="cb-isian__bantu">PIN diberikan kepada staf toko oleh kantor pusat.</span>
                    </div>
                </div>

                <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-3">
                    Simpan hasil verifikasi
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
