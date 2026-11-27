@extends('layouts.app')

@section('judul', 'Buku Alamat')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li>Buku Alamat</li>
            </ol>
            <h1>Buku Alamat</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                <div class="cb-kepala-bagian">
                    <h2>Alamat Pengiriman</h2>
                    <a class="cb-tombol cb-tombol--kecil cb-tombol--utama" href="{{ route('alamat.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah alamat
                    </a>
                </div>

                @if($daftarAlamat->isEmpty())
                    <p class="cb-catatan">
                        Belum ada alamat tersimpan.
                        <a href="{{ route('alamat.create') }}">Tambahkan alamat pertama Anda</a>.
                    </p>
                @else
                    <div class="cb-kisi cb-kisi--dua">
                        @foreach($daftarAlamat as $alamat)
                            <div class="cb-panel">
                                <p class="mb-2">
                                    <span class="cb-lencana">{{ $alamat->label }}</span>
                                    @if($alamat->is_primary)
                                        <span class="cb-lencana cb-lencana--berhasil">Alamat utama</span>
                                    @endif
                                </p>

                                <p class="mb-1" style="font-weight: 700">{{ $alamat->recipient }}</p>
                                <p class="mb-1">{{ $alamat->phone }}</p>
                                <p class="mb-3" style="color: var(--cb-tinta-lembut)">
                                    {{ $alamat->detail }}<br>
                                    @if($alamat->district){{ $alamat->district }}, @endif
                                    {{ $alamat->city }}, {{ $alamat->province }}
                                    @if($alamat->postal_code) {{ $alamat->postal_code }} @endif
                                </p>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                       href="{{ route('alamat.edit', $alamat->id) }}">Ubah</a>

                                    @if(! $alamat->is_primary)
                                        <form action="{{ route('alamat.utama', $alamat->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="cb-tombol cb-tombol--kecil cb-tombol--garis">
                                                Jadikan utama
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cb-tombol cb-tombol--kecil cb-tombol--polos">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
