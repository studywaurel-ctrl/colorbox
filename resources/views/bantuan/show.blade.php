@extends('layouts.app')

@section('judul', $judul)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li><a href="{{ route('bantuan.index') }}">Bantuan</a></li>
            <li>{{ $judul }}</li>
        </ol>
        <h1>{{ $judul }}</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">{{ $ringkasan }}</p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div style="max-width: 70ch">
            @foreach($isi as $bagian)
                <h2 style="font-size: var(--cb-h3); margin-top: var(--cb-s6)">{{ $bagian['judul'] }}</h2>

                @foreach($bagian['isi'] as $paragraf)
                    <p style="color: var(--cb-tinta-lembut)">{{ $paragraf }}</p>
                @endforeach

                @if(isset($bagian['daftar']))
                    <ul style="color: var(--cb-tinta-lembut)">
                        @foreach($bagian['daftar'] as $butir)
                            <li>{{ $butir }}</li>
                        @endforeach
                    </ul>
                @endif

                @if(isset($bagian['tabel']))
                    <div class="cb-gulir-datar">
                        <table class="cb-tabel">
                            <thead>
                            <tr>
                                @foreach($bagian['tabel']['kepala'] as $kolom)
                                    <th>{{ $kolom }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bagian['tabel']['baris'] as $baris)
                                <tr>
                                    @foreach($baris as $sel)
                                        <td>{{ $sel }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(isset($bagian['tautan']))
                    <div class="d-flex gap-3 flex-wrap mt-3">
                        @foreach($bagian['tautan'] as $tautan)
                            <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
                               href="{{ $tautan[2] ? route($tautan[1], $tautan[2]) : route($tautan[1]) }}">
                                {{ $tautan[0] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-kepala-bagian">
            <div>
                <h2 style="font-size: var(--cb-h3)">Halaman bantuan lainnya</h2>
            </div>
        </div>
        <div class="cb-saring__kiri">
            @foreach(\App\Http\Controllers\BantuanController::TOPIK as $alamat => $keterangan)
                @if($alamat !== $halaman)
                    <a class="cb-cip" href="{{ route('bantuan.show', $alamat) }}">{{ $keterangan[0] }}</a>
                @endif
            @endforeach
        </div>
    </section>

</div>
@endsection
