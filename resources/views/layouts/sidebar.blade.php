<div class="app-sidebar colored">
  <div class="sidebar-header">
    <a class="header-brand" href="{{ route('arsip.index') }}">
      <div class="logo-img">
        <img src="{{ $logo }}" class="header-brand-img" alt="" style="width: 100%">
      </div>
      <span class="text" style="font-size: 0.87em">Aplikasi Sekolah</span>
    </a>
    <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
    <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
  </div>

  <div class="sidebar-content">
    <div class="nav-container">
      <nav id="main-menu-navigation" class="navigation-main">
        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'pegawai')
          <div class="nav-lavel">DATA ARSIP</div>
          <div class="nav-item{{ Request::url()==route('arsip.index')?' active':'' }}">
            <a href="{{ route('arsip.index') }}"><i class="ik ik-bar-chart-2"></i><span>Status Arsip</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('siswa.index'))!==false?' active':'' }}">
            <a href="{{ route('siswa.index') }}"><i class="ik ik-users"></i><span>Siswa</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('pegawai.index'))!==false?' active':'' }}">
            <a href="{{ route('pegawai.index') }}"><i class="fas fa-user-tie"></i><span>Guru & Pegawai</span></a>
          </div>
          <div class="nav-lavel">DATA ABSENSI</div>
          <div class="nav-item{{ Request::url()==route('absensi.index')?' active':'' }}">
            <a href="{{ route('absensi.index') }}"><i class="ik ik-bar-chart-2"></i><span>Status Absensi</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('absensi.ruang.index'))!==false?' active':'' }}">
            <a href="{{ route('absensi.ruang.index') }}"><i class="fas fa-dungeon"></i><span>Ruang Absensi</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('absensi.jadwal.index'))!==false?' active':'' }}">
            <a href="{{ route('absensi.jadwal.index') }}"><i class="fas fa-clock"></i><span>Jadwal Absensi</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('absensi.jadwal.user.index'))!==false?' active':'' }}">
            <a href="{{ route('absensi.jadwal.user.index') }}"><i class="fas fa-users"></i><span>Jadwal Absen User</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('absensi.desc.index'))!==false?' active':'' }}">
            <a href="{{ route('absensi.desc.index') }}"><i class="fas fa-edit"></i><span>Keterangan Absensi</span></a>
          </div>
          <div class="nav-item{{ strpos(Request::url(),route('absensi.log.index'))!==false?' active':'' }}">
            <a href="{{ route('absensi.log.index') }}"><i class="fas fa-clipboard-list"></i><span>Absensi Log</span></a>
          </div>
          <div class="nav-lavel">PENGGAJIAN</div>
          <div class="nav-item{{ strpos(Request::url(),route('payroll.user.index'))!==false?' active':'' }}">
            <a href="{{ route('payroll.user.index') }}"><i class="fas fa-money-bill-wave"></i><span>Gaji Pegawai</span></a>
          </div>
          <div class="nav-item{{ Request::url()==route('payroll.log.index')?' active':'' }}">
            <a href="{{ route('payroll.log.index') }}"><i class="fas fa-file-invoice"></i><span>Hitung Gaji Pegawai</span></a>
          </div>
        @endif
        <div class="nav-lavel">PENGATURAN</div>
        <div class="nav-item{{ Request::url()==route('configs')?' active':'' }}">
          <a href="{{ route('configs') }}"><i class="ik ik-settings"></i><span>Pengaturan Sistem</span></a>
        </div>
        <div class="nav-item">
          <a href="{{ route('logout') }}"><i class="ik ik-power"></i><span>Keluar</span></a>
        </div>
      </nav>
    </div>
  </div>
</div>
