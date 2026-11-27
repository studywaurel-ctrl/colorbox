@extends('layouts.app')

@section('judul', 'Keluhan')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li>Keluhan</li>
            </ol>
            <h1>Keluhan</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                {{-- Formulir keluhan baru --}}
                <section class="cb-panel mb-4">
                    <div class="cb-kepala-bagian">
                        <h2>Kirim Keluhan Baru</h2>
                    </div>

                    <form class="cb-borang" action="{{ route('keluhan.store') }}" method="POST">
                        @csrf

                        <div class="cb-isian">
                            <label class="cb-label" for="subject">Judul keluhan</label>
                            <input class="cb-masukan" type="text" id="subject" name="subject"
                                   value="{{ old('subject') }}" maxlength="120" required
                                   placeholder="Contoh: Barang yang diterima berbeda warna">
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="order_id">Pesanan terkait (boleh dikosongkan)</label>
                            <select class="cb-masukan" id="order_id" name="order_id">
                                <option value="">Tidak terkait pesanan tertentu</option>
                                @foreach($daftarPesanan as $pesanan)
                                    <option value="{{ $pesanan->id }}" @if((string) old('order_id') === (string) $pesanan->id) selected @endif>
                                        {{ $pesanan->order_code }} &mdash; {{ tanggal_id($pesanan->created_at) }} &mdash; {{ rupiah($pesanan->total) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="cb-isian__bantu">Memilih pesanan membantu tim kami memeriksa lebih cepat.</span>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="message">Isi keluhan</label>
                            <textarea class="cb-masukan" id="message" name="message" rows="5" required
                                      placeholder="Tuliskan kejadiannya selengkap mungkin.">{{ old('message') }}</textarea>
                        </div>

                        <div>
                            <button type="submit" class="cb-tombol cb-tombol--utama">Kirim keluhan</button>
                        </div>
                    </form>
                </section>

                {{-- Daftar keluhan yang pernah dikirim --}}
                <div class="cb-kepala-bagian">
                    <h2>Keluhan Anda</h2>
                </div>

                @if($daftarKeluhan->isEmpty())
                    <p class="cb-catatan">Belum ada keluhan yang dikirim.</p>
                @else
                    @foreach($daftarKeluhan as $keluhan)
                        <div class="cb-panel mb-3">
                            <p class="mb-2">
                                <span class="cb-lencana
                                    @if($keluhan->status === 'selesai') cb-lencana--berhasil
                                    @elseif($keluhan->status === 'diproses') cb-lencana--ingat
                                    @endif">{{ ucfirst($keluhan->status) }}</span>
                                @if($keluhan->pesanan)
                                    <span class="cb-lencana cb-lencana--aksen">{{ $keluhan->pesanan->order_code }}</span>
                                @endif
                            </p>

                            <p class="mb-1" style="font-weight: 700">{{ $keluhan->subject }}</p>
                            <p class="mb-1">{{ $keluhan->message }}</p>
                            <p class="mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                                Dikirim {{ tanggal_id($keluhan->created_at, true) }}
                            </p>

                            @if($keluhan->response)
                                <div class="cb-catatan cb-catatan--berhasil mt-3">
                                    <strong>Jawaban tim Colorbox:</strong><br>
                                    {{ $keluhan->response }}
                                    @if($keluhan->responded_at)
                                        <br>
                                        <span style="font-size: var(--cb-mini)">
                                            Dijawab {{ tanggal_id($keluhan->responded_at, true) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
