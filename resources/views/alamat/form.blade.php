@extends('layouts.app')

@section('judul', $alamat ? 'Ubah Alamat' : 'Tambah Alamat')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li><a href="{{ route('alamat.index') }}">Buku Alamat</a></li>
                <li>{{ $alamat ? 'Ubah Alamat' : 'Tambah Alamat' }}</li>
            </ol>
            <h1>{{ $alamat ? 'Ubah Alamat' : 'Tambah Alamat' }}</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                <div class="cb-panel">
                    <form class="cb-borang"
                          action="{{ $alamat ? route('alamat.update', $alamat->id) : route('alamat.store') }}"
                          method="POST">
                        @csrf
                        @if($alamat)
                            @method('PUT')
                        @endif

                        <div class="cb-isian">
                            <label class="cb-label" for="label">Label alamat</label>
                            <input class="cb-masukan" type="text" id="label" name="label"
                                   value="{{ old('label', $alamat ? $alamat->label : 'Rumah') }}"
                                   placeholder="Rumah, Kantor, Kos">
                            <span class="cb-isian__bantu">Untuk memudahkan Anda mengenali alamat ini.</span>
                        </div>

                        <div class="cb-baris-isian cb-baris-isian--dua">
                            <div class="cb-isian">
                                <label class="cb-label" for="recipient">Nama penerima</label>
                                <input class="cb-masukan" type="text" id="recipient" name="recipient"
                                       value="{{ old('recipient', $alamat ? $alamat->recipient : '') }}" required>
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="phone">Nomor telepon</label>
                                <input class="cb-masukan" type="tel" id="phone" name="phone"
                                       value="{{ old('phone', $alamat ? $alamat->phone : '') }}"
                                       placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="cb-baris-isian cb-baris-isian--dua">
                            <div class="cb-isian">
                                <label class="cb-label" for="province">Provinsi</label>
                                <input class="cb-masukan" type="text" id="province" name="province"
                                       value="{{ old('province', $alamat ? $alamat->province : '') }}" required>
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="city">Kota atau kabupaten</label>
                                <input class="cb-masukan" type="text" id="city" name="city"
                                       value="{{ old('city', $alamat ? $alamat->city : '') }}" required>
                            </div>
                        </div>

                        <div class="cb-baris-isian cb-baris-isian--dua">
                            <div class="cb-isian">
                                <label class="cb-label" for="district">Kecamatan</label>
                                <input class="cb-masukan" type="text" id="district" name="district"
                                       value="{{ old('district', $alamat ? $alamat->district : '') }}">
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="postal_code">Kode pos</label>
                                <input class="cb-masukan" type="text" id="postal_code" name="postal_code"
                                       value="{{ old('postal_code', $alamat ? $alamat->postal_code : '') }}">
                            </div>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="detail">Alamat lengkap</label>
                            <textarea class="cb-masukan" id="detail" name="detail" rows="3" required>{{ old('detail', $alamat ? $alamat->detail : '') }}</textarea>
                            <span class="cb-isian__bantu">Tulis nama jalan, nomor rumah, RT/RW, dan patokan bila ada.</span>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-pilihan__label" for="is_primary">
                                <input type="checkbox" id="is_primary" name="is_primary" value="1"
                                       @if(old('is_primary', $alamat ? $alamat->is_primary : false)) checked @endif>
                                Jadikan alamat utama
                            </label>
                            <span class="cb-isian__bantu">Alamat utama dipakai lebih dulu saat checkout.</span>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="cb-tombol cb-tombol--utama">
                                {{ $alamat ? 'Simpan perubahan' : 'Simpan alamat' }}
                            </button>
                            <a class="cb-tombol cb-tombol--garis" href="{{ route('alamat.index') }}">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
