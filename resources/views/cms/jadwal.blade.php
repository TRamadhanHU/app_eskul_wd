@extends('layouts.app')

@section('title', 'Jadwal ')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="container p-3">
                        <div class="row">
                            <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal"
                                data-target="#TambahModal">
                                Tambah
                            </button>
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @foreach ($eskuls as $eskul)
                                        <li class="nav-item" role="presentation">
                                            <button class="tab-eskul nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="eskul-{{ $eskul->id }}-tab" data-toggle="tab"
                                                data-target="#eskul-{{ $eskul->id }}" type="button" role="tab"
                                                aria-controls="eskul-{{ $eskul->id }}"
                                                aria-selected="false">{{ $eskul->nama }}</button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @foreach ($eskuls as $eskul)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="eskul-{{ $eskul->id }}" role="tabpanel"
                                            aria-labelledby="eskul-{{ $eskul->id }}-tab">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>
                                                        <th>Jam</th>
                                                        <th>Nama</th>
                                                        <th>Deskripsi</th>
                                                        @if (Auth::user()->hasPermission('jadwal_manage'))
                                                            <th class="text-center">Aksi</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (array_key_exists($eskul->id, $data))
                                                        @forelse ($data[$eskul->id] as $items)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $hari[$items['hari']] }}</td>
                                                                <td>
                                                                    {{ date('H:i', strtotime($items['waktu_mulai'])) }} -
                                                                    {{ date('H:i', strtotime($items['waktu_selesai'])) }}
                                                                </td>
                                                                <td>{{ $items['nama'] }}</td>
                                                                <td>{{ $items['desc'] }}</td>
                                                                @if (Auth::user()->hasPermission('jadwal_manage'))
                                                                    <td>
                                                                        <div class="d-flex justify-content-center">
                                                                            <a href="#"
                                                                                class="btn btn-primary mr-1 editBtn"
                                                                                data-id="{{ $items['id'] }}">Edit</a>
                                                                            <a href="#"
                                                                                class="btn btn-danger deleteBtn"
                                                                                data-id="{{ $items['id'] }}">Hapus</a>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center">Data Kosong</td>
                                                            </tr>
                                                        @endforelse
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center">Data Kosong</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="TambahModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Jadwal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('jadwal_eskuls.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="eskul_id">Eskul</label>
                                <select name="eskul_id" id="eskul_id" class="form-control">
                                    @foreach ($eskuls as $eskul)
                                        <option value="{{ $eskul->id }}">{{ $eskul->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="hari">Hari</label>
                                <select name="hari" id="hari" class="form-control">
                                    @foreach ($hari as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan</label>
                                <input type="text" name="desc" id="kegiatan" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="jam_mulai">Jam Mulai</label>
                                    <input type="time" name="waktu_mulai" id="jam_mulai" class="form-control" required>
                                </div>
    
                                <div class="form-group col-6">
                                    <label for="jam_selesai">Jam Selesai</label>
                                    <input type="time" name="waktu_selesai" id="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Jadwal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('jadwal_eskuls.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="idEdit">
                            <div class="form-group">
                                <label for="eskul_id">Eskul</label>
                                <select name="eskul_id" id="eskul_id_edit" class="form-control">
                                    @foreach ($eskuls as $eskul)
                                        <option value="{{ $eskul->id }}">{{ $eskul->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="hari">Hari</label>
                                <select name="hari" id="hari_edit" class="form-control">
                                    @foreach ($hari as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan</label>
                                <input type="text" name="desc" id="kegiatan_edit" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="jam_mulai">Jam Mulai</label>
                                    <input type="time" name="waktu_mulai" id="jam_mulai_edit" class="form-control" required>
                                </div>
    
                                <div class="form-group col-6">
                                    <label for="jam_selesai">Jam Selesai</label>
                                    <input type="time" name="waktu_selesai" id="jam_selesai_edit" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete modal --}}
        <div class="modal fade" id="DelModal" tabindex="-1" aria-labelledby="DelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DelModalLabel">Hapus Jadwal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" id="delForm">
                        <div class="modal-body">
                            @csrf
                            @method('DELETE')
                            <p>Apakah anda yakin ingin menghapus Jadwal ini?</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                let eskulId = localStorage.setItem('eskulId', $('.tab-eskul.active').attr('id').split('-')[1]);
                $('.tab-eskul').on('click', function() {
                    let eskulId = $(this).attr('id').split('-')[1];
                    localStorage.setItem('eskulId', eskulId);
                    $('#eskul_id').val(eskulId);
                });
            });

            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/master/jadwal/${id}`,
                    method: 'GET',
                    success: function(data) {
                        $('#idEdit').val(data.id);
                        $('#eskul_id_edit').val(data.eskul_id);
                        $('#hari_edit').val(data.hari);
                        $('#kegiatan_edit').val(data.desc);
                        $('#jam_mulai_edit').val(data.waktu_mulai);
                        $('#jam_selesai_edit').val(data.waktu_selesai);
                        $('#EditModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                let id = $(this).data('id');
                let url = "{{ route('jadwal_eskuls.delete', ':id') }}";
                url = url.replace(':id', id);
                $('#delForm').attr('action', url);
                $('#DelModal').modal('show');
            });
        </script>
    @endpush
