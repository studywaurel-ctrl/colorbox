@extends('layouts.app')

@section('judul', 'Notifikasi')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li>Notifikasi</li>
            </ol>
            <h1>Notifikasi</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                <div class="cb-kepala-bagian">
                    <h2>
                        Kabar Terbaru
                        @if($jumlahBelumDibaca > 0)
                            <span class="cb-lencana cb-lencana--aksen">{{ $jumlahBelumDibaca }} belum dibaca</span>
                        @endif
                    </h2>

                    @if($jumlahBelumDibaca > 0)
                        <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                            @csrf
                            <button type="submit" class="cb-tombol cb-tombol--kecil cb-tombol--garis">
                                Tandai semua sudah dibaca
                            </button>
                        </form>
                    @endif
                </div>

                @if($daftarNotifikasi->isEmpty())
                    <p class="cb-catatan">Belum ada notifikasi untuk akun Anda.</p>
                @else
                    <div class="cb-rincian">
                        @foreach($daftarNotifikasi as $notifikasi)
                            <div class="cb-panel mb-3 @if(! $notifikasi->read_at) cb-panel--aksen @endif">
                                <p class="mb-1" style="font-weight: 700">
                                    {{ $notifikasi->title }}
                                    @if(! $notifikasi->read_at)
                                        <span class="cb-lencana cb-lencana--baru">Baru</span>
                                    @endif
                                </p>
                                <p class="mb-1">{{ $notifikasi->body }}</p>
                                <p class="mb-2" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                                    {{ tanggal_id($notifikasi->created_at, true) }}
                                </p>
                                <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                   href="{{ route('notifikasi.buka', $notifikasi->id) }}">Buka</a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $daftarNotifikasi->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
