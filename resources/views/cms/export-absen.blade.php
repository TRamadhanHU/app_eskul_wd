<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td rowspan="2">No</td>
            <td rowspan="2">Nama</td>
            <td rowspan="2">Kelas</td>
            <td colspan="{{ array_key_exists($eskulId, $listTanggal) ? count($listTanggal[$eskulId]) : 0 }}">Tanggal</td>
            <td colspan="2">Jumlah</td>
        </tr>
        <tr class="table-row-head">
            @if (array_key_exists($eskulId, $listTanggal))
                @foreach ($listTanggal[$eskulId] as $tgl)
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
            if (array_key_exists($eskulId, $namaSiswa)) {
                foreach ($namaSiswa[$eskulId] as $anggota) {
                    $anggotaAbsen[$anggota['id']] = 0;
                }
            }
        @endphp
        @if (array_key_exists($eskulId, $namaSiswa))
            @foreach ($namaSiswa[$eskulId] as $anggota)
                @if ($anggota['eskul_id'] == $eskulId)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $anggota['nama'] }}</td>
                        <td>{{ $anggota['kelas'] }}</td>
                        @if (array_key_exists($eskulId, $listTanggal))
                           @foreach ($listTanggal[$eskulId] as $tgl)
                            <td>
                                @if (array_key_exists($eskulId, $listAbsen))
                                    @if (array_key_exists($anggota['id'], $listAbsen[$eskulId]))
                                        @if (array_key_exists($tgl, $listAbsen[$eskulId][$anggota['id']]))
                                            {{ $listAbsen[$eskulId][$anggota['id']][$tgl] ? 'Hadir' : '-'}}
                                            @php
                                                if (array_key_exists($anggota['id'], $anggotaAbsen)) {
                                                    $anggotaAbsen[$anggota['id']] += $listAbsen[$eskulId][$anggota['id']][$tgl] ? 1 : 0;
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
                            @if (array_key_exists($eskulId, $listAbsen))
                                @if (array_key_exists($anggota['id'], $listAbsen[$eskulId]))
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