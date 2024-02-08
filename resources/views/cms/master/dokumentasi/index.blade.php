@extends('layouts.app')

@section('title', 'Dokumentasi')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center w-100">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="p-4">
                        <div class="d-flex align-items-center">
                            <div class="col">
                                <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                                    data-target="#TambahModal">
                                    Tambah
                                </button>
                            </div>
                            <div class="col-md-7 d-flex justify-content-end">
                                <div class="col-md-3">
                                    <select class="form-control" id="eskulSelect">
                                        <option value="">Pilih Eskul</option>
                                        @foreach ($eskul as $key => $value)
                                            <option value="{{ $value->id }}" @if (request()->get('eskul') == $value->id) selected @endif>
                                                {{ $value->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="monthSelect">
                                        @php $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; @endphp
                                        <option value="">Pilih Bulan</option>
                                        @foreach ($bulan as $key => $value)
                                            <option value="{{ $key + 1 }}" @if (request()->get('bulan') == $key + 1) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button id="btnFilter" class="btn btn-primary">Filter</button>
                                    {{-- export --}}
                                    <button class="btn btn-success ml-2" id="exportBtn">Export</button>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="ExportData">
                            <h2 class="text-center w-100 mb-3 hidden-view" style="color: #000; font-weight: bold; font-size: 24px; text-transform: uppercase;">
                                Dokumentasi Eskul
                                <span class="d-block" style="font-size: 18px; font-weight: normal;">
                                    SMK Widya Digrantara
                                </span>
                            </h2>
                            @forelse ($data as $key => $value)
                                <div class="col-md-4 mb-3 card-wrapper">
                                    <div class="card">
                                        <img src="/storage/dokumentasi/{{ $value->path }}" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ $value->eskul_nama }} - {{ date('d F Y', strtotime($value->tanggal))}}
                                            </h5>
                                            <p class="card-text">
                                                {{ $value->jadwal_desc }}
                                            </p>
                                            <div class="d-flex">
                                                <a href="#" class="btn btn-danger deleteBtn hidden-export" data-id="{{ $value->id }}">Hapus</a>
                                                {{-- download --}}
                                                <a href="/storage/dokumentasi/{{ $value->path }}" class="btn btn-success ml-2 hidden-export">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center p-4">
                                    <p>Data Kosong</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasPermission('dokumentasi_manage'))
        <div class="modal fade" id="TambahModal" tabindex="-1" aria-labelledby="TambahModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TambahModalLabel">Tambah Dokumentasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('dokumentasi.upsert') }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="gambarUpload">upload foto dokumentasi</label>
                                <input type="file" class="form-control-file" id="gambarUpload" name="gambar">
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jadwalEskul">Jadwal Eskul</label>
                                <select class="form-control" id="jadwalEskul" name="jadwal_id">
                                    <option value="">Pilih Jadwal Eskul</option>
                                    @foreach ($jadwal as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->eskul_nama }} - {{ date('h:i', strtotime($value->waktu_mulai)) }} s/d {{ date('h:i', strtotime($value->waktu_selesai)) }}</option>
                                    @endforeach
                                </select>
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
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            let url = "/list-dokumentasi/:id/delete";
            url = url.replace(':id', id);
            $('#delForm').attr('action', url);
            $('#DelModal').modal('show');
        });

        $('#btnFilter').on('click', function() {
            let eskul = $('#eskulSelect').val();
            let bulan = $('#monthSelect').val();
            let url = "/list-dokumentasi?eskul=" + eskul + "&bulan=" + bulan;
            window.location.href = url;
        });

        $('#exportBtn').on('click', function() {
            $('.hidden-export').hide();
            let printContents = document.getElementById('ExportData').innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        });
    </script>
@endpush
