@extends('layouts.app')

@section('title', 'Jadwal ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="container p-3">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <select class="form-control" id="eskul">
                                <option value="">Pilih Eskul</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#TambahModal" >
                        Tambah
                        </button>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hari</th>
                                        <th>Kegiatan</th>
                                        <th>Jam</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $data as $data )
                                        <tr>
                                        <td>1</td>
                                        <td>senin 2 maret 2004</td>
                                        <td>latihan</td>
                                        <td>05:00</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                
                                                <a href="#" class="btn btn-primary">Edit</a>
                                                <a href="#" class="btn btn-danger">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                            {{-- @foreach ($eskuls as $eskul)
                                                <option value="{{ $eskul->id }}">{{ $eskul->nama_eskul }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="hari">Hari</label>
                                        <input type="text" name="hari" id="hari" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="kegiatan">Kegiatan</label>
                                        <input type="text" name="kegiatan" id="kegiatan" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="jam">Jam</label>
                                        <input type="time" name="jam" id="jam" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection