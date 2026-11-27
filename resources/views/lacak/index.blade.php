@extends('layouts.app')

@section('judul', 'Lacak Pesanan')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Lacak Pesanan</li>
        </ol>
        <h1>Lacak Pesanan</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Masukkan kode pesanan yang Anda terima setelah memesan, contohnya CBX-20260101-0001.
        </p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <form action="{{ route('lacak.cari') }}" method="POST" class="cb-borang" style="max-width: 520px">
            @csrf
            <div class="cb-isian">
                <label class="cb-label" for="kode">Kode pesanan</label>
                <input class="cb-masukan" type="text" id="kode" name="kode" required maxlength="30"
                       value="{{ old('kode', $kode) }}" placeholder="CBX-20260101-0001">
                <p class="cb-isian__bantu">Kode pesanan juga tercantum pada halaman rincian pesanan di akun Anda.</p>
            </div>
            <button type="submit" class="cb-tombol cb-tombol--utama">Lacak Pesanan</button>
        </form>
    </section>

    @if($pesanan)
        <section class="cb-bagian cb-bagian--rapat">
            <div class="cb-kepala-bagian">
                <div>
                    <h2 style="font-size: var(--cb-h3)">Pesanan {{ $pesanan->order_code }}</h2>
                    <p>Dibuat pada {{ tanggal_id($pesanan->created_at, true) }}.</p>
                </div>
                <span class="cb-lencana cb-lencana--aksen">{{ $pesanan->labelStatus() }}</span>
            </div>

            <div class="cb-kisi cb-kisi--dua">
                <div class="cb-panel">
                    <h3 style="font-size: var(--cb-h4)">Keterangan pesanan</h3>
                    <ul class="cb-rincian-biaya">
                        <li><span>Cara menerima</span>
                            <span>{{ $pesanan->method === 'ambil_toko' ? 'Diambil di toko' : 'Dikirim kurir' }}</span></li>
                        @if($pesanan->toko)
                            <li><span>Toko pengambilan</span><span>{{ $pesanan->toko->name }}</span></li>
                        @endif
                        @if($pesanan->pengiriman && $pesanan->pengiriman->tracking_number)
                            <li><span>Nomor resi</span><span>{{ $pesanan->pengiriman->tracking_number }}</span></li>
                        @endif
                        @if($pesanan->paid_at)
                            <li><span>Dibayar pada</span><span>{{ tanggal_id($pesanan->paid_at, true) }}</span></li>
                        @endif
                        <li class="cb-jumlah"><span>Total</span><span>{{ rupiah($pesanan->total) }}</span></li>
                    </ul>
                </div>

                <div class="cb-panel">
                    <h3 style="font-size: var(--cb-h4)">Barang dalam pesanan</h3>
                    @forelse($pesanan->item as $barang)
                        <p class="mb-2" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                            {{ $barang->product_name }}
                            <span style="color: var(--cb-tinta-samar)">
                                ({{ $barang->color }} / {{ $barang->size }}) &times; {{ $barang->quantity }}
                            </span>
                        </p>
                    @empty
                        <p class="mb-0" style="font-size: var(--cb-kecil)">Rincian barang tidak tersedia.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="cb-bagian cb-bagian--rapat">
            <div class="cb-kepala-bagian">
                <div>
                    <h2 style="font-size: var(--cb-h3)">Riwayat Status</h2>
                    <p>Setiap perubahan status pesanan dicatat di sini.</p>
                </div>
            </div>

            @if($riwayat->count() > 0)
                <ol class="cb-langkah">
                    @foreach($riwayat as $catatan)
                        <li class="{{ $loop->first ? 'tercapai' : '' }}">
                            <p class="cb-langkah__judul mb-1">{{ ucfirst(str_replace('_', ' ', $catatan->status)) }}</p>
                            <p class="mb-1" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                {{ $catatan->description }}
                            </p>
                            <p class="cb-langkah__waktu mb-0">
                                {{ tanggal_id($catatan->happened_at, true) }}
                                @if($catatan->happened_at) pukul {{ $catatan->happened_at->format('H:i') }} @endif
                            </p>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="cb-catatan">Belum ada catatan riwayat untuk pesanan ini.</p>
            @endif
        </section>
    @endif

</div>
@endsection
