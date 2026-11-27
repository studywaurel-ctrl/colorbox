@if(session('berhasil') || session('ingat') || session('gagal') || $errors->any())
    <div class="cb-wadah" style="padding-top: var(--cb-s4)">
        @if(session('berhasil'))
            <p class="cb-catatan cb-catatan--berhasil">{{ session('berhasil') }}</p>
        @endif
        @if(session('ingat'))
            <p class="cb-catatan cb-catatan--ingat">{{ session('ingat') }}</p>
        @endif
        @if(session('gagal'))
            <p class="cb-catatan">{{ session('gagal') }}</p>
        @endif
        @if($errors->any())
            <div class="cb-catatan">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $pesan)
                        <li>{{ $pesan }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
