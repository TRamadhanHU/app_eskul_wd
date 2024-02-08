@extends('layouts.app')

@section('title', 'Anggota')

@section('content')
    <div class="container">
        <div class="row justify-content-center w-100">
            <div class="col-md-12">
                <div class="card">
                    <div class="container p-3">
                        @if(Auth::user()->hasPermission('anggota_manage'))
                        <div class="row mb-5">
                            <div class="col-md-4 my-1">
                                <select class="form-control" id="eskulSelect">
                                    <option value="">Pilih Extrakulikuler</option>
                                    @foreach ($listEskul as $key => $value)
                                        <option value="{{ $value['id'] }}"
                                            {{ request()->get('eskul') == $value['id'] ? 'selected' : '' }}>{{ $value['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-1">
                                <select class="form-control" id="kelasSelect">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($listKelas as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ request()->get('kelas') == $value ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 my-1">
                                <select class="form-control" id="angkatanSelect">
                                    <option value="">Pilih Angkatan</option>
                                    @php $fiveYearsAgo = date('Y') - 5; @endphp
                                    @for ($i = 0; $i <= 5; $i++)
                                        <option value="{{ $fiveYearsAgo + $i }}"
                                            {{ request()->get('angkatan') == $fiveYearsAgo + $i ? 'selected' : '' }}>
                                            {{ $fiveYearsAgo + $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start">
                                <div class="input-group mb-3 pr-2">
                                    <input type="text" class="form-control" placeholder="Search" name="search"
                                        value="{{ request()->get('search') }}" id="searchInput">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="search">
                                            Search
                                        </button>
                                    </div>
                                </div>
                                <a href="{{ route('anggota') }}" class="btn btn-danger mb-3">Clear</a>
                            </div>
                            @if (Auth::user()->hasPermission('anggota_manage'))
                                <div class="col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal"
                                        data-target="#TambahModal">
                                        Tambah
                                    </button>
                                    <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal"
                                        data-target="#ImportAnggota">
                                        Import
                                    </button>
                                    <button type="button" class="btn btn-primary ml-3 mb-3" id="exportBtn">
                                        Export
                                    </button>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Jurusan</th>
                                            <th>Angkatan</th>
                                            @if (Auth::user()->hasPermission('anggota_manage'))
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data['nama'] }}</td>
                                                <td>{{ $data['kelas'] }}</td>
                                                <td>{{ $data['jurusan'] }}</td>
                                                <td>{{ $data['angkatan'] }}</td>
                                                @if (Auth::user()->hasPermission('anggota_manage'))
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="#" class="btn btn-primary mr-1 editBtn"
                                                                data-id="{{ $data['id'] }}">Edit</a>
                                                            <a href="#" class="btn btn-danger deleteBtn"
                                                                data-id="{{ $data['id'] }}">Hapus</a>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasPermission('anggota_manage'))
        <div class="modal fade" id="TambahModal" tabindex="-1" aria-labelledby="TambahModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TambahModalLabel">Tambah Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('anggota.upsert') }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="number" name="kelas" class="form-control" placeholder="Kelas"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="jurusan">Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" placeholder="Jurusan"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="angkatan">Angkatan</label>
                                <input type="number" name="angkatan" class="form-control" placeholder="Angkatan"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="eskul">Extrakulikuler</label>
                                <select class="form-control" name="eskul_id">
                                    <option value="">Pilih Extrakulikuler</option>
                                    @foreach ($listEskul as $key => $value)
                                        <option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    autocomplete="off">
                            </div>                      
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditModalLabel">Tambah Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('anggota.upsert') }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="idEdit">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama"
                                    autocomplete="off" id="nameEdit">
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="number" name="kelas" class="form-control" placeholder="Kelas"
                                    autocomplete="off" id="kelasEdit">
                            </div>
                            <div class="form-group">
                                <label for="jurusan">Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" placeholder="Jurusan"
                                    autocomplete="off" id="jurusanEdit">
                            </div>
                            <div class="form-group">
                                <label for="angkatan">Angkatan</label>
                                <input type="number" name="angkatan" class="form-control" placeholder="Angkatan"
                                    autocomplete="off" id="angkatanEdit">
                            </div>
                            <div class="form-group">
                                <label for="eskul">Extrakulikuler</label>
                                <select class="form-control" name="eskul_id" id="eskulEdit">
                                    <option value="">Pilih Extrakulikuler</option>
                                    @foreach ($listEskul as $key => $value)
                                        <option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email"
                                    autocomplete="off" id="emailEdit">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    autocomplete="off">
                            </div>                      
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                        
        
        <div class="modal fade" id="ImportAnggota" tabindex="-1" aria-labelledby="ImportAnggotaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ImportAnggotaLabel">Import Data Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('anggota.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">File Excel</label>
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                            <a href="{{ route('anggota.template-import') }}" class="btn btn-success">Download Template</a>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="DelModal" tabindex="-1" aria-labelledby="DelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DelModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" id="delForm">
                        <div class="modal-body">
                            @csrf
                            @method('DELETE')
                            <p>Apakah anda yakin ingin menghapus data ini?</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        $('#kelasSelect').on('change', function() {
            let kelas = $(this).val();
            let url = "{{ route('anggota') }}";
            let eskul = "{{ request()->eskul }}";
            let angkatan = "{{ request()->angkatan }}";
            let search = "{{ request()->search }}";
            if (kelas) {
                url = url + '?kelas=' + kelas + '&eskul=' + eskul + '&search=' + search + '&angkatan=' + angkatan
            }
            window.location.href = url;
        });

        $('#eskulSelect').on('change', function() {
            let eskul = $(this).val();
            let url = "{{ route('anggota') }}";
            let kelas = "{{ request()->kelas }}";
            let angkatan = "{{ request()->angkatan }}";
            let search = "{{ request()->search }}";
            if (eskul) {
                url = url + '?kelas=' + kelas + '&eskul=' + eskul + '&search=' + search + '&angkatan=' + angkatan
            }
            window.location.href = url;
        });

        // search
        $('#search').on('click', function() {
            let search = $('#searchInput').val();
            let url = "{{ route('anggota') }}";
            let eskul = "{{ request()->eskul }}";
            let kelas = "{{ request()->kelas }}";
            let angkatan = "{{ request()->angkatan }}";
            if (search) {
                url = url + '?search=' + search + '&eskul=' + eskul + '&kelas=' + kelas + '&angkatan=' + angkatan
            }
            window.location.href = url;
        });

        $('#angkatanSelect').on('change', function() {
            let angkatan = $(this).val();
            let url = "{{ route('anggota') }}";
            let eskul = "{{ request()->eskul }}";
            let kelas = "{{ request()->kelas }}";
            let search = "{{ request()->search }}";
            if (angkatan) {
                url = url + '?angkatan=' + angkatan + '&eskul=' + eskul + '&kelas=' + kelas + '&search=' + search
            }
            window.location.href = url
        });

        $('#exportBtn').on('click', function() {
            let eskul = "{{ request()->eskul }}";
            let kelas = "{{ request()->kelas }}";
            let angkatan = "{{ request()->angkatan }}";

            let url = "{{ route('anggota.export') }}";
            window.location.href = url + '?eskul=' + eskul + '&kelas=' + kelas + '&angkatan=' + angkatan;
        });

        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            let url = "{{ route('anggota.show', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#idEdit').val(res.id);
                    $('#nameEdit').val(res.nama);
                    $('#kelasEdit').val(res.kelas);
                    $('#jurusanEdit').val(res.jurusan);
                    $('#angkatanEdit').val(res.angkatan);
                    $('#eskulEdit').val(res.eskul_id);
                    $('#emailEdit').val(res.email);
                    $('#EditModal').modal('show');
                }
            });
        });
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            let url = "{{ route('anggota.delete', ':id') }}";
            url = url.replace(':id', id);
            $('#delForm').attr('action', url);
            $('#DelModal').modal('show');
        });
    </script>
@endpush
