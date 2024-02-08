@extends('layouts.app')

@section('title', 'List Absensi')

@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="h3 mb-0 text-gray-800">List Absensi</h1>
                        </div>
                        <div class="row col-12">
                            <div class="col-md-3 mb-2">
                                <select name="kelas" id="kelas" class="form-control">
                                    <option value="">Kelas</option>
                                    <option value="10" {{ request()->kelas == 10 ? 'selected' : '' }}>10</option>
                                    <option value="11" {{ request()->kelas == 11 ? 'selected' : '' }}>11</option>
                                    <option value="12" {{ request()->kelas == 12 ? 'selected' : '' }}>12</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">Bulan</option>
                                    <option value="1" {{ request()->bulan == 1 ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ request()->bulan == 2 ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ request()->bulan == 3 ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ request()->bulan == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ request()->bulan == 5 ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ request()->bulan == 6 ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ request()->bulan == 7 ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ request()->bulan == 8 ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ request()->bulan == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ request()->bulan == 10 ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ request()->bulan == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ request()->bulan == 12 ? 'selected' : '' }}>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">Tahun</option>
                                    @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}"
                                            {{ request()->tahun ? (request()->tahun == $i ? 'selected' : '') : (date('Y') == $i ? 'selected' : '') }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if (request()->kelas || request()->bulan || request()->tahun)
                                    <a href="{{ route('list-absensi') }}" class="btn btn-danger">Clear</a>
                                @endif
                                <a href="{{ route('absensi.export', ['kelas' => request()->kelas, 'bulan' => request()->bulan, 'tahun' => request()->tahun]) }}"
                                    class="btn btn-success">Export</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mb-3">
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
                                    <div class="table-responsive">
                                        <div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="table-wrapper">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <td rowspan="2">No</td>
                                                                    <td rowspan="2">Nama</td>
                                                                    <td rowspan="2">Kelas</td>
                                                                    <td
                                                                        colspan="{{ array_key_exists($eskul->id, $listTanggal) ? count($listTanggal[$eskul->id]) : 0 }}">
                                                                        Tanggal</td>
                                                                    <td colspan="2">Jumlah</td>
                                                                </tr>
                                                                <tr class="table-row-head">
                                                                    @if (array_key_exists($eskul->id, $listTanggal))
                                                                        @foreach ($listTanggal[$eskul->id] as $tgl)
                                                                            <td>{{ $tgl }}</td>
                                                                        @endforeach
                                                                    @else
                                                                        <td colspan="0">-</td>
                                                                    @endif
                                                                    <td>Jml Hadir</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-body-content">
                                                                @php
                                                                    $anggotaAbsen = [];
                                                                    if (array_key_exists($eskul->id, $namaSiswa)) {
                                                                        foreach ($namaSiswa[$eskul->id] as $anggota) {
                                                                            $anggotaAbsen[$anggota['id']] = 0;
                                                                        }
                                                                    }
                                                                @endphp
                                                                @if (array_key_exists($eskul->id, $namaSiswa))
                                                                    @foreach ($namaSiswa[$eskul->id] as $anggota)
                                                                        @if ($anggota['eskul_id'] == $eskul->id)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $anggota['nama'] }}</td>
                                                                                <td>{{ $anggota['kelas'] }}</td>
                                                                                @if (array_key_exists($eskul->id, $listTanggal))
                                                                                    @foreach ($listTanggal[$eskul->id] as $tgl)
                                                                                        <td>
                                                                                            @if (array_key_exists($eskul->id, $listAbsen))
                                                                                                @if (array_key_exists($anggota['id'], $listAbsen[$eskul->id]))
                                                                                                    @if (array_key_exists($tgl, $listAbsen[$eskul->id][$anggota['id']]))
                                                                                                        {{ $listAbsen[$eskul->id][$anggota['id']][$tgl] ? 'Hadir' : '-' }}
                                                                                                        @php
                                                                                                            if (array_key_exists($anggota['id'], $anggotaAbsen)) {
                                                                                                                $anggotaAbsen[$anggota['id']] += $listAbsen[$eskul->id][$anggota['id']][$tgl] ? 1 : 0;
                                                                                                            }
                                                                                                        @endphp
                                                                                                    @else
                                                                                                        -
                                                                                                    @endif
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                        </td>
                                                                                    @endforeach
                                                                                @else
                                                                                    <td colspan="0">-</td>
                                                                                @endif
                                                                                <td>
                                                                                    @if (array_key_exists($eskul->id, $listAbsen))
                                                                                        @if (array_key_exists($anggota['id'], $listAbsen[$eskul->id]))
                                                                                            {{ $anggotaAbsen[$anggota['id']] }}
                                                                                        @else
                                                                                            -
                                                                                        @endif
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // check if bulan is selected
            const bulan = '{{ request()->bulan }}';
            if (bulan) {
                $('#bulan').val(bulan);
            } else {
                let thisMonth = new Date().getMonth() + 1;
                $('#bulan').val(thisMonth);
            }
            setTimeout(() => {
                const lastTab = localStorage.getItem('lastTab');
                if (lastTab) {
                    $(`#${lastTab}`).click();
                }
            }, 1);
        });

        $('.tab-eskul').on('click', function() {
            localStorage.setItem('lastTab', $(this).attr('id'));
        });
    </script>
@endpush
