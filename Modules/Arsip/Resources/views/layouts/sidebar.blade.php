<div class="app-sidebar colored">
  <div class="sidebar-header">
    <a class="header-brand" href="{{ route('arsip.index') }}">
      <div class="logo-img">
        <img src="{{ asset('assets/img/sinjai.png') }}" class="header-brand-img" alt="" style="width: 100%">
      </div>
      <span class="text" style="font-size: 0.87em">SMPN 39 Sinjai</span>
    </a>
    <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
    <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
  </div>

  <div class="sidebar-content">
    <div class="nav-container">
      <nav id="main-menu-navigation" class="navigation-main">
        <div class="nav-lavel">DATA ARSIP</div>
        <div class="nav-item{{ Request::url()==route('arsip.index')?' active':'' }}">
          <a href="{{ route('arsip.index') }}"><i class="ik ik-bar-chart-2"></i><span>Beranda</span></a>
        </div>
        <div class="nav-item{{ strpos(Request::url(),route('siswa.index'))!==false?' active':'' }}">
          <a href="{{ route('siswa.index') }}"><i class="ik ik-users"></i><span>Siswa</span></a>
        </div>
        <div class="nav-item{{ strpos(Request::url(),route('pegawai.index'))!==false?' active':'' }}">
          <a href="{{ route('pegawai.index') }}"><i class="fas fa-user-tie"></i><span>Guru & Pegawai</span></a>
        </div>
        <div class="nav-lavel">PENGATURAN</div>
        <div class="nav-item{{ Request::url()==route('profile')?' active':'' }}">
          <a href="{{ route('profile') }}"><i class="ik ik-settings"></i><span>Pengaturan Akun</span></a>
        </div>
        <div class="nav-item">
          <a href="{{ route('logout') }}"><i class="ik ik-power"></i><span>Keluar</span></a>
        </div>
      </nav>
    </div>
  </div>
</div>
