@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container">
        <div class="row justify-content-center w-100">
            <div class="col-md-12">
                <div class="card">
                    <div class="container p-3">
                        <div class="row">
                            @if (Auth::user()->hasPermission('eskul_manage'))
                                <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal"
                                    data-target="#TambahModal">
                                    Tambah
                                </button>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Deskripsi</th>
                                            @if (Auth::user()->hasPermission('eskul_manage'))
                                                <th class="text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data['id'] }}</td>
                                                <td>{{ $data['nama'] }}</td>
                                                <td>{{ $data['desc'] }}</td>
                                                @if (Auth::user()->hasPermission('eskul_manage'))
                                                    <td>
                                                        <div class="d-flex justify-content-center">
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
                                                <td colspan="5" class="text-center">Data Kosong</td>
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

    @if (Auth::user()->hasPermission('eskul_manage'))
        <div class="modal fade" id="TambahModal" tabindex="-1" aria-labelledby="TambahModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TambahModalLabel">Tambah Eskul</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('eskul.upsert') }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            {{-- nama, email, password, role --}}
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama"
                                    autocomplete="off">
                            </div>
                            {{-- disable --}}
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="desc" id="desc" class="form-control"></textarea>
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

        {{-- edit modal --}}
        <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditModalLabel">Edit Eskul</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('eskul.upsert') }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="idEdit">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="name" id="nameEdit" class="form-control"
                                    placeholder="Nama" autocomplete="off">
                            </div>
                            {{-- disable --}}
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="desc" id="descEdit" class="form-control"></textarea>
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

        {{-- delete modal --}}
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
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            let url = "{{ route('eskul.show', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#idEdit').val(res.id);
                    $('#nameEdit').val(res.nama);
                    $('#descEdit').val(res.desc);

                    $('#EditModal').modal('show');
                }
            });
        });
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            let url = "{{ route('eskul.delete', ':id') }}";
            url = url.replace(':id', id);
            $('#delForm').attr('action', url);
            $('#DelModal').modal('show');
        });
    </script>
@endpush
