@extends('layouts.app')

@section('judul', 'Pesanan '.$pesanan->order_code)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li><a href="{{ route('pesanan.index') }}">Pesanan Saya</a></li>
            <li>{{ $pesanan->order_code }}</li>
        </ol>
        <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
            <h1>{{ $pesanan->order_code }}</h1>
            <span class="cb-lencana {{ $pesanan->status === 'dibatalkan' ? 'cb-lencana--ingat' : 'cb-lencana--aksen' }}">
                {{ $pesanan->labelStatus() }}
            </span>
        </div>
        <p style="color: var(--cb-tinta-lembut)">
            Dibuat pada {{ tanggal_id($pesanan->created_at, true) }} pukul {{ $pesanan->created_at->format('H:i') }}
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">

            <section class="mb-5">
                <h2 style="font-size: var(--cb-h3)">Perjalanan Pesanan</h2>
                @if($riwayat->count() > 0)
                    <ol class="cb-langkah">
                        @foreach($riwayat as $catatan)
                            <li class="tercapai">
                                <span class="cb-langkah__judul">{{ ucfirst(str_replace('_', ' ', $catatan->status)) }}</span>
                                <span class="d-block" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                    {{ $catatan->description }}
                                </span>
                                <span class="cb-langkah__waktu d-block">
                                    {{ tanggal_id($catatan->happened_at, true) }}
                                    @if($catatan->happened_at) pukul {{ $catatan->happened_at->format('H:i') }} @endif
                                </span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="cb-catatan">Belum ada catatan perjalanan untuk pesanan ini.</p>
                @endif

                @if($pesanan->pengiriman && $pesanan->pengiriman->tracking_number)
                    <p class="cb-catatan cb-catatan--aksen">
                        Pesanan ini dibawa {{ $pesanan->pengiriman->courier }} {{ $pesanan->pengiriman->service }}
                        dengan nomor resi {{ $pesanan->pengiriman->tracking_number }}.
                    </p>
                @endif
            </section>

            <section class="mb-5">
                <h2 style="font-size: var(--cb-h3)">Barang yang Dipesan</h2>
                @foreach($pesanan->item as $baris)
                    <div class="cb-barang">
                        <div class="cb-barang__gambar">
                            <img src="{{ gambar_produk($baris->image_path) }}" alt="{{ $baris->product_name }}">
                        </div>
                        <div>
                            <p class="cb-barang__nama mb-1">
                                @if($baris->varian && $baris->varian->produk)
                                    <a href="{{ route('produk.show', $baris->varian->produk) }}">{{ $baris->product_name }}</a>
                                @else
                                    {{ $baris->product_name }}
                                @endif
                            </p>
                            <p class="cb-barang__ket mb-0">
                                {{ $baris->color }} · {{ $baris->size }} · x{{ $baris->quantity }}
                            </p>
                            @if($baris->reviewed)
                                <span class="cb-lencana cb-lencana--berhasil mt-2">Sudah diulas</span>
                            @endif
                        </div>
                        <div class="text-end" style="font-weight: 600">{{ rupiah($baris->subtotal) }}</div>
                    </div>
                @endforeach
            </section>

            @if($bisaUlas)
                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Tulis Ulasan</h2>
                    @php $belumDiulas = $pesanan->item->where('reviewed', false); @endphp

                    @if($belumDiulas->count() > 0)
                        <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                            Ceritakan pengalaman Anda memakai produk berikut supaya membantu pembeli lain.
                        </p>

                        @foreach($belumDiulas as $baris)
                            <div class="cb-panel mb-3">
                                <p class="mb-1" style="font-weight: 600">{{ $baris->product_name }}</p>
                                <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                    {{ $baris->color }} · {{ $baris->size }}
                                </p>

                                <form action="{{ route('ulasan.store', $pesanan) }}" method="POST" class="cb-borang">
                                    @csrf
                                    <input type="hidden" name="order_item_id" value="{{ $baris->id }}">

                                    <div class="cb-isian">
                                        <label class="cb-label" for="rating-{{ $baris->id }}">Penilaian</label>
                                        <select class="cb-masukan" id="rating-{{ $baris->id }}" name="rating" required>
                                            <option value="5">5 — Sangat memuaskan</option>
                                            <option value="4">4 — Memuaskan</option>
                                            <option value="3">3 — Cukup</option>
                                            <option value="2">2 — Kurang</option>
                                            <option value="1">1 — Tidak memuaskan</option>
                                        </select>
                                    </div>

                                    <div class="cb-isian">
                                        <label class="cb-label" for="comment-{{ $baris->id }}">Ulasan (opsional)</label>
                                        <textarea class="cb-masukan" id="comment-{{ $baris->id }}" name="comment"
                                                  rows="3" maxlength="500"
                                                  placeholder="Contoh: bahannya adem dan ukurannya pas."></textarea>
                                    </div>

                                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--kecil">
                                        Kirim ulasan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p class="cb-catatan cb-catatan--berhasil">
                            Terima kasih, seluruh barang pada pesanan ini sudah Anda ulas.
                        </p>
                    @endif
                </section>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="cb-panel cb-menempel">
                <h2 style="font-size: var(--cb-h4)">Ringkasan Pembayaran</h2>
                <ul class="cb-rincian-biaya">
                    <li><span>Subtotal</span><span>{{ rupiah($pesanan->subtotal) }}</span></li>
                    <li><span>Ongkos kirim</span>
                        <span>{{ $pesanan->shipping_cost > 0 ? rupiah($pesanan->shipping_cost) : 'Gratis' }}</span></li>
                    @if($pesanan->discount > 0)
                        <li class="cb-hemat"><span>Potongan voucher</span><span>&minus; {{ rupiah($pesanan->discount) }}</span></li>
                    @endif
                    @if($pesanan->points_used > 0)
                        <li class="cb-hemat"><span>Potongan poin ({{ $pesanan->points_used }} poin)</span>
                            <span>&minus; {{ rupiah($pesanan->points_used * (int) setting('point_value', 100)) }}</span></li>
                    @endif
                    <li class="cb-jumlah"><span>Total</span><span>{{ rupiah($pesanan->total) }}</span></li>
                </ul>

                <hr>
                <dl class="row g-2 mb-0" style="font-size: var(--cb-kecil)">
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Status pembayaran</dt>
                    <dd class="col-7 mb-0">
                        {{ $pesanan->pembayaran ? ucfirst(str_replace('_', ' ', $pesanan->pembayaran->payment_status)) : 'Belum ada' }}
                    </dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Cara bayar</dt>
                    <dd class="col-7 mb-0">{{ $pesanan->pembayaran?->payment_method ?: 'Belum dipilih' }}</dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Cara menerima</dt>
                    <dd class="col-7 mb-0">{{ $pesanan->method === 'ambil_toko' ? 'Diambil di toko' : 'Dikirim kurir' }}</dd>
                    @if($pesanan->alamat)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Dikirim ke</dt>
                        <dd class="col-7 mb-0">
                            {{ $pesanan->alamat->recipient }} — {{ $pesanan->alamat->detail }}, {{ $pesanan->alamat->city }}
                        </dd>
                    @endif
                    @if($pesanan->toko)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Toko pengambilan</dt>
                        <dd class="col-7 mb-0">{{ $pesanan->toko->name }}, {{ $pesanan->toko->city }}</dd>
                    @endif
                    @if($pesanan->note)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Catatan</dt>
                        <dd class="col-7 mb-0">{{ $pesanan->note }}</dd>
                    @endif
                </dl>

                <div class="d-grid gap-2 mt-4">
                    @if($pesanan->status === 'menunggu_pembayaran' && $pesanan->pembayaran)
                        <a class="cb-tombol cb-tombol--utama" href="{{ route('pembayaran.show', $pesanan->pembayaran) }}">
                            Bayar sekarang
                        </a>
                    @endif

                    @if(in_array($pesanan->status, ['dikirim', 'siap_diambil']))
                        <form action="{{ route('pesanan.terima', $pesanan) }}" method="POST"
                              data-konfirmasi="Tandai pesanan ini sudah Anda terima?">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh">
                                Barang diterima
                            </button>
                        </form>
                    @endif

                    @if($bisaRetur)
                        <a class="cb-tombol cb-tombol--garis" href="{{ route('retur.create', $pesanan->order_code) }}">
                            Ajukan retur atau penukaran
                        </a>
                    @elseif(in_array($pesanan->status, ['diterima', 'selesai']))
                        <p class="mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                            Batas pengajuan retur {{ $hariRetur }} hari sejak barang diterima sudah lewat.
                        </p>
                    @endif

                    @if($pesanan->bisaDibatalkan())
                        <form action="{{ route('pesanan.batal', $pesanan) }}" method="POST"
                              data-konfirmasi="Batalkan pesanan ini? Stok dan poin akan dikembalikan.">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--penuh">
                                Batalkan pesanan
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('pesanan.beli-lagi', $pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--penuh">
                            <i class="bi bi-arrow-repeat"></i> Beli lagi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
