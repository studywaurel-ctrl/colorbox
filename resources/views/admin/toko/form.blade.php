@extends('layouts.admin')

@section('judul', $toko ? 'Ubah Toko' : 'Tambah Toko')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">{{ $toko ? 'Ubah Toko: '.$toko->name : 'Tambah Toko Baru' }}</h2>

    <form class="cb-borang mt-3" method="POST"
          action="{{ $toko ? route('admin.toko.update', $toko) : route('admin.toko.store') }}">
        @csrf
        @if($toko)
            @method('PUT')
        @endif

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="code">Kode toko</label>
                <input class="cb-masukan" type="text" id="code" name="code" maxlength="10" required
                       value="{{ old('code', $toko->code ?? '') }}" placeholder="CBX01">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="name">Nama toko</label>
                <input class="cb-masukan" type="text" id="name" name="name" maxlength="120" required
                       value="{{ old('name', $toko->name ?? '') }}">
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="city">Kota</label>
                <input class="cb-masukan" type="text" id="city" name="city" maxlength="60" required
                       value="{{ old('city', $toko->city ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="floor">Lantai</label>
                <input class="cb-masukan" type="text" id="floor" name="floor" maxlength="40"
                       value="{{ old('floor', $toko->floor ?? '') }}" placeholder="Lantai 2 Unit 21">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label" for="address">Alamat</label>
            <input class="cb-masukan" type="text" id="address" name="address" maxlength="191" required
                   value="{{ old('address', $toko->address ?? '') }}">
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="phone">Telepon</label>
                <input class="cb-masukan" type="text" id="phone" name="phone" maxlength="20"
                       value="{{ old('phone', $toko->phone ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label">Jam layanan</label>
                <div class="d-flex gap-2">
                    <input class="cb-masukan" type="time" name="open_time" required
                           value="{{ old('open_time', $toko ? substr($toko->open_time, 0, 5) : '10:00') }}">
                    <input class="cb-masukan" type="time" name="close_time" required
                           value="{{ old('close_time', $toko ? substr($toko->close_time, 0, 5) : '22:00') }}">
                </div>
                <p class="cb-isian__bantu">Jam buka dan jam tutup.</p>
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="latitude">Latitude</label>
                <input class="cb-masukan" type="text" id="latitude" name="latitude"
                       value="{{ old('latitude', $toko->latitude ?? '') }}" placeholder="-6.229700">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="longitude">Longitude</label>
                <input class="cb-masukan" type="text" id="longitude" name="longitude"
                       value="{{ old('longitude', $toko->longitude ?? '') }}" placeholder="106.808700">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label">Layanan dan status</label>
            <div class="cb-pilihan__daftar">
                <label class="cb-pilihan__label">
                    <input type="checkbox" name="has_ttc" value="1" @checked(old('has_ttc', $toko->has_ttc ?? true))>
                    Melayani Tap Try and Collect
                </label>
                <label class="cb-pilihan__label">
                    <input type="checkbox" name="has_donation" value="1" @checked(old('has_donation', $toko->has_donation ?? true))>
                    Menerima donasi pakaian
                </label>
                <label class="cb-pilihan__label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $toko->is_active ?? true))>
                    Toko aktif
                </label>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="cb-tombol cb-tombol--utama" type="submit">{{ $toko ? 'Simpan Perubahan' : 'Simpan Toko' }}</button>
            <a class="cb-tombol cb-tombol--polos" href="{{ route('admin.toko.index') }}">Batal</a>
        </div>
    </form>
</div>

@endsection
