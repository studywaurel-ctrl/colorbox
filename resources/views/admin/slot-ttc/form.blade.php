@extends('layouts.admin')

@section('judul', 'Buat Slot Tap Try and Collect')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Buat Slot Tap Try and Collect</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Slot dibuat sekaligus untuk rentang tanggal yang dipilih, paling panjang 90 hari.
        Slot yang sudah ada tidak ditimpa supaya jumlah booking-nya tetap aman.
        Jadwal ini hanya dipakai layanan Tap Try and Collect, terpisah dari slot donasi.
    </p>

    <form class="cb-borang mt-3" action="{{ route('admin.slot-ttc.store') }}" method="POST">
        @csrf

        <div class="cb-isian">
            <label class="cb-label" for="store_id">Toko yang melayani Tap Try and Collect</label>
            <select class="cb-masukan" id="store_id" name="store_id" required>
                <option value="">Pilih toko</option>
                @foreach($daftarToko as $toko)
                    <option value="{{ $toko->id }}" @selected((string) old('store_id') === (string) $toko->id)>
                        {{ $toko->name }} — {{ $toko->city }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="dari">Tanggal mulai</label>
                <input class="cb-masukan" type="date" id="dari" name="dari" required value="{{ old('dari') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="sampai">Tanggal akhir</label>
                <input class="cb-masukan" type="date" id="sampai" name="sampai" required value="{{ old('sampai') }}">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label">Jam layanan</label>
            <div class="cb-pilihan__daftar">
                @forelse($daftarJam as $jam)
                    <label class="cb-pilihan__label">
                        <input type="checkbox" name="jam[]" value="{{ $jam }}"
                               @checked(is_array(old('jam')) && in_array($jam, old('jam')))>
                        {{ $jam }}
                    </label>
                @empty
                    <p class="mb-0">Daftar jam layanan belum diatur pada berkas konfigurasi.</p>
                @endforelse
            </div>
            <p class="cb-isian__bantu">Centang paling sedikit satu jam. Setiap slot berdurasi satu jam.</p>
        </div>

        <div class="cb-isian">
            <label class="cb-label" for="quota">Kuota tiap slot</label>
            <input class="cb-masukan" type="number" id="quota" name="quota" min="1" max="200" required
                   value="{{ old('quota', $kuotaBawaan) }}" style="max-width: 160px">
            <p class="cb-isian__bantu">Jumlah pelanggan yang boleh mencoba pada satu jam yang sama.</p>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="cb-tombol cb-tombol--utama" type="submit">Simpan Slot</button>
            <a class="cb-tombol cb-tombol--polos" href="{{ route('admin.slot-ttc.index') }}">Batal</a>
        </div>
    </form>
</div>

@endsection
