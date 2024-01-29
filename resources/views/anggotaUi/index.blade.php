
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Aplikasi Eskul WD</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVkkFg8kAsa4wR6D_50rA1Q9SDSA9LeM2_l8LUiLOCzlx4gCgnh59n1bsk0nvGzoCQMoM&usqp=CAU" alt="user" class="rounded-circle" width="50px" height="50px">
        <a class="navbar-brand font-weight-bold" href="#">
            Absensi Eskul WD
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarUser" aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse pt-3" id="navbarUser">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item text-center">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
          </ul>
        </div>
      </nav>

      {{-- make nav with image user in left and menu in right --}}
      
      <main role="main" class="w-100 p-3">
        <div class="rounded p-2 shadow" style="border-width: 2px !important;">
            <h4 class="font-weight-bold text-info">
                Hallo, <span class="text-dark">{{ $data['nama'] }}</span>
            </h4>
            <div class="row">
                <div class="col-6">
                    <h5 class="font-weight-bold">
                        <span id="date" class="text-dark"></span>
                    </h5>
                </div>
                <div class="col-6">
                    <h5 class="font-weight-bold d-flex justify-content-end">
                        <span id="time" class="text-dark"></span>
                    </h5>
                </div>
                <div class="col-6">
                    <small>
                        tanggal
                    </small>
                </div>
                <div class="col-6">
                    <small class="d-flex justify-content-end">
                        waktu
                    </small>
                </div>
            </div>
            <button class="btn btn-info btn-block font-weight-bold mt-3" data-toggle="modal" data-target="#modalAbsen">
                <i class="fas fa-qrcode"></i>
                Absen
            </button>
        </div>

        {{-- history absensi --}}
        <div class="mt-3">
            <h5 class="font-weight-bold text-info">
                History Absensi
            </h5>
            <div class="mb-2">
                <select name="bulan" id="bulan" class="form-control">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="wrapper py-2">
                <p class="mb-1 text-xs text-center">
                    list absensi
                </p>
                @forelse ($data['absen']->items() as $absensi)
                <div class="rounded p-2 shadow-sm mb-1" style="border-width: 2px !important;">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="font-weight-bold">
                                <span class="text-dark">{{ $absensi->eskul }} - <small>{{ $absensi->desc }}</small></span>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="font-weight-bold d-flex justify-content-end">
                                <span class="text-dark">{{ ucfirst($absensi->keterangan) }}</span>
                            </h5>
                        </div>
                        <div class="col-6">
                            <small>
                               {{ date('d F Y', strtotime($absensi->waktu)) }}
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="d-flex justify-content-end">
                                {{ date('H:i', strtotime($absensi->waktu)) }}
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded p-2 shadow-sm mb-1" style="border-width: 2px !important;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="font-weight-bold">
                                <span class="text-dark">Tidak ada data absensi</span>
                            </h5>
                        </div>
                    </div>
                @endforelse
                {{-- @if ($data['absen']->lastPage() > 1)
                <div class="d-flex justify-content-center mt-4">
                    {{ $data['absen']->links() }}
                </div>
                @endif --}}
            </div>
        </div>
      </main>
  
    <div class="modal fade" id="modalAbsen" tabindex="-1" aria-labelledby="modalAbsenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
            <div class="d-flex justify-content-between">
                <h5 class="font-weight-bold">
                    Pilh Jadwal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <div class="wrapper py-2">
                @forelse ($listJadwalHariIni as $jadwal)
                <div class="rounded p-2 shadow-sm mb-1" style="border-width: 2px !important;">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="font-weight-bold">
                                <span class="text-dark">{{ $jadwal->eskul }}</span>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="font-weight-bold d-flex justify-content-end">
                                <span class="text-dark">{{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}</span>
                            </h5>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <small>
                                {{ $jadwal->desc }}
                            </small>
                            <a href="/absensi/siswa/{{ $jadwal->id }}/{{ $data['anggota']->id}}" class="btn btn-info btn-sm">
                                <i class="fas fa-check"></i>
                                Hadir
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded p-2 shadow-sm mb-1" style="border-width: 2px !important;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="font-weight-bold">
                                <span class="text-dark">Tidak ada jadwal / Jadwal sudah selesai</span>
                            </h5>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        </div>
    </div>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.min.js"></script>
    <script src="/js/Chart.min.js"></script>
    @stack('scripts')
</body>

</html>

<script>
    $(document).ready(function() {
        startTime();
        let today = new Date();
        // format date jun/12
        let date = today.getDate();
        let month = today.getMonth() + 1;
        let year = today.getFullYear();
        let day = today.getDay();
        let dayList = ["Minggu","Senin","Selasa","Rabu ","Kamis","Jumat","Sabtu"];
        let dayName = dayList[day];
        let formattedDate = dayName + ', ' + date + '/' + month + '/' + year;
        document.getElementById('date').innerText = formattedDate;
    });
    window.mobileAndTabletCheck = function() {
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    };
    if (!mobileAndTabletCheck()) {
        document.body.innerHTML = '';
        let alert = document.createElement('div');
        alert.classList.add('alert', 'alert-danger', 'text-center', 'mt-5');
        alert.innerHTML = '<h1 class="font-weight-bold">Hanya bisa diakses melalui mobile</h1>';
        document.body.appendChild(alert);
    }

    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('time').innerText =  h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};
        return i;
    }
</script>