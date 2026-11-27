@extends('layouts.admin')

@section('judul', 'Pengaturan')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Aturan Bisnis</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Nilai di bawah ini dipakai seluruh halaman, misalnya batas ongkir gratis, poin per
        potong donasi, dan batas hari pengajuan retur. Kunci yang bertanda angka hanya boleh
        diisi angka dan tidak boleh dikosongkan.
    </p>

    @if($daftar->count() === 0)
        <p class="mb-0">Belum ada pengaturan pada basis data.</p>
    @else
        <form class="cb-borang mt-3" action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')

            @foreach($daftar as $baris)
                <div class="cb-isian">
                    <label class="cb-label" for="nilai-{{ $baris->key }}">
                        {{ $baris->key }}
                        @if(in_array($baris->key, $kunciAngka))
                            <span class="cb-lencana cb-lencana--aksen">Angka</span>
                        @endif
                    </label>
                    @if(in_array($baris->key, $kunciAngka))
                        <input class="cb-masukan" type="number" step="any" min="0"
                               id="nilai-{{ $baris->key }}" name="nilai[{{ $baris->key }}]" required
                               value="{{ old('nilai.'.$baris->key, $baris->value) }}" style="max-width: 260px">
                    @else
                        <input class="cb-masukan" type="text" maxlength="500"
                               id="nilai-{{ $baris->key }}" name="nilai[{{ $baris->key }}]"
                               value="{{ old('nilai.'.$baris->key, $baris->value) }}">
                    @endif
                    <p class="cb-isian__bantu">{{ $baris->description ?: 'Tanpa keterangan.' }}</p>
                </div>
            @endforeach

            <div class="d-flex gap-2 mt-4">
                <button class="cb-tombol cb-tombol--utama" type="submit">Simpan Pengaturan</button>
                <a class="cb-tombol cb-tombol--polos" href="{{ route('admin.pengaturan.index') }}">Batal</a>
            </div>
        </form>
    @endif
</div>

@endsection
