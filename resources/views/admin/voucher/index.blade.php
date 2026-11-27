@extends('layouts.admin')

@section('judul', 'Voucher')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Tambah Voucher</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Jenis persen memotong sekian persen dari belanja, jenis potongan tetap memotong
        sejumlah rupiah. Batas potongan hanya dipakai untuk voucher persen.
    </p>

    <form class="cb-borang mt-2" action="{{ route('admin.voucher.store') }}" method="POST">
        @csrf

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="code">Kode voucher</label>
                <input class="cb-masukan" type="text" id="code" name="code" maxlength="30" required
                       value="{{ old('code') }}" placeholder="HEMAT10">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="name">Nama voucher</label>
                <input class="cb-masukan" type="text" id="name" name="name" maxlength="100" required
                       value="{{ old('name') }}" placeholder="Potongan 10 persen">
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="type">Jenis potongan</label>
                <select class="cb-masukan" id="type" name="type" required>
                    <option value="percent" @selected(old('type') === 'percent')>Persen</option>
                    <option value="amount" @selected(old('type') === 'amount')>Potongan tetap (rupiah)</option>
                </select>
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="value">Nilai potongan</label>
                <input class="cb-masukan" type="number" id="value" name="value" min="1" max="10000000" required
                       value="{{ old('value') }}">
                <p class="cb-isian__bantu">Isi 10 untuk 10 persen, atau 25000 untuk potongan Rp25.000.</p>
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="max_discount">Batas potongan (Rp)</label>
                <input class="cb-masukan" type="number" id="max_discount" name="max_discount" min="0" max="10000000"
                       value="{{ old('max_discount') }}" placeholder="Boleh dikosongkan">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="min_purchase">Belanja paling sedikit (Rp)</label>
                <input class="cb-masukan" type="number" id="min_purchase" name="min_purchase" min="0" max="100000000"
                       value="{{ old('min_purchase', 0) }}">
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="quota">Kuota pemakaian</label>
                <input class="cb-masukan" type="number" id="quota" name="quota" min="1" max="100000" required
                       value="{{ old('quota', 100) }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="expired_at">Berlaku sampai</label>
                <input class="cb-masukan" type="date" id="expired_at" name="expired_at" value="{{ old('expired_at') }}">
                <p class="cb-isian__bantu">Boleh dikosongkan bila voucher tidak punya batas waktu.</p>
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-pilihan__label">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                Voucher aktif
            </label>
        </div>

        <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Voucher</button>
    </form>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Daftar Voucher</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        {{ $daftar->total() }} voucher tercatat. Voucher yang sudah pernah dipakai tidak bisa
        dihapus, cukup dinonaktifkan. Buka "Ubah" untuk menyunting satu voucher.
    </p>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Potongan</th>
                <th>Belanja Minimal</th>
                <th>Kuota</th>
                <th>Terpakai</th>
                <th>Berlaku Sampai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $voucher)
                <tr>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->name }}</td>
                    <td>
                        @if($voucher->type === 'percent')
                            {{ $voucher->value }}%
                            @if($voucher->max_discount)
                                <br><span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">paling banyak {{ rupiah($voucher->max_discount) }}</span>
                            @endif
                        @else
                            {{ rupiah($voucher->value) }}
                        @endif
                    </td>
                    <td>{{ rupiah($voucher->min_purchase) }}</td>
                    <td>{{ $voucher->quota }}</td>
                    <td>{{ $voucher->used }}</td>
                    <td>{{ $voucher->expired_at ? tanggal_id($voucher->expired_at) : 'Tanpa batas' }}</td>
                    <td>
                        @if($voucher->is_active)
                            <span class="cb-lencana cb-lencana--berhasil">Aktif</span>
                        @else
                            <span class="cb-lencana cb-lencana--ingat">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.voucher.destroy', $voucher) }}" method="POST"
                              data-konfirmasi="Hapus voucher {{ $voucher->code }}?">
                            @csrf
                            @method('DELETE')
                            <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="9">
                        <details>
                            <summary class="cb-label" style="cursor: pointer">Ubah voucher {{ $voucher->code }}</summary>

                            <form class="cb-borang mt-2" action="{{ route('admin.voucher.update', $voucher) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="cb-baris-isian cb-baris-isian--dua">
                                    <div class="cb-isian">
                                        <label class="cb-label" for="code-{{ $voucher->id }}">Kode voucher</label>
                                        <input class="cb-masukan" type="text" id="code-{{ $voucher->id }}" name="code"
                                               maxlength="30" required value="{{ $voucher->code }}">
                                    </div>
                                    <div class="cb-isian">
                                        <label class="cb-label" for="name-{{ $voucher->id }}">Nama voucher</label>
                                        <input class="cb-masukan" type="text" id="name-{{ $voucher->id }}" name="name"
                                               maxlength="100" required value="{{ $voucher->name }}">
                                    </div>
                                </div>

                                <div class="cb-baris-isian cb-baris-isian--dua">
                                    <div class="cb-isian">
                                        <label class="cb-label" for="type-{{ $voucher->id }}">Jenis potongan</label>
                                        <select class="cb-masukan" id="type-{{ $voucher->id }}" name="type" required>
                                            <option value="percent" @selected($voucher->type === 'percent')>Persen</option>
                                            <option value="amount" @selected($voucher->type === 'amount')>Potongan tetap (rupiah)</option>
                                        </select>
                                    </div>
                                    <div class="cb-isian">
                                        <label class="cb-label" for="value-{{ $voucher->id }}">Nilai potongan</label>
                                        <input class="cb-masukan" type="number" id="value-{{ $voucher->id }}" name="value"
                                               min="1" max="10000000" required value="{{ $voucher->value }}">
                                    </div>
                                </div>

                                <div class="cb-baris-isian cb-baris-isian--dua">
                                    <div class="cb-isian">
                                        <label class="cb-label" for="max_discount-{{ $voucher->id }}">Batas potongan (Rp)</label>
                                        <input class="cb-masukan" type="number" id="max_discount-{{ $voucher->id }}" name="max_discount"
                                               min="0" max="10000000" value="{{ $voucher->max_discount }}">
                                    </div>
                                    <div class="cb-isian">
                                        <label class="cb-label" for="min_purchase-{{ $voucher->id }}">Belanja paling sedikit (Rp)</label>
                                        <input class="cb-masukan" type="number" id="min_purchase-{{ $voucher->id }}" name="min_purchase"
                                               min="0" max="100000000" value="{{ $voucher->min_purchase }}">
                                    </div>
                                </div>

                                <div class="cb-baris-isian cb-baris-isian--dua">
                                    <div class="cb-isian">
                                        <label class="cb-label" for="quota-{{ $voucher->id }}">Kuota pemakaian</label>
                                        <input class="cb-masukan" type="number" id="quota-{{ $voucher->id }}" name="quota"
                                               min="1" max="100000" required value="{{ $voucher->quota }}">
                                    </div>
                                    <div class="cb-isian">
                                        <label class="cb-label" for="expired_at-{{ $voucher->id }}">Berlaku sampai</label>
                                        <input class="cb-masukan" type="date" id="expired_at-{{ $voucher->id }}" name="expired_at"
                                               value="{{ $voucher->expired_at ? $voucher->expired_at->format('Y-m-d') : '' }}">
                                    </div>
                                </div>

                                <div class="cb-isian">
                                    <label class="cb-pilihan__label">
                                        <input type="checkbox" name="is_active" value="1" @checked($voucher->is_active)>
                                        Voucher aktif
                                    </label>
                                </div>

                                <button class="cb-tombol cb-tombol--kecil" type="submit">Simpan Perubahan</button>
                            </form>
                        </details>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">Belum ada voucher.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
