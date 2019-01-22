  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU UTAMA</li>
        <li class="{{ pcrsMenuActiveCondition('dashboardcontroller_index', $collection->get('activeMenu')) }}">
          <a href="{{ route('dashboard') }}">
            <i class="fa fa-dashboard"></i></i> <span>Dashboard</span>
          </a>
        </li>
        @can('view-anggota')
        <li class="{{ pcrsMenuActiveCondition('anggotacontroller_index', $collection->get('activeMenu')) }}">
          <a href="{{ route('anggota') }}">
            <i class="fa fa-user"></i></i> <span>Maklumat Anggota</span>
          </a>
        </li>
        @endcan
        @can('view-setting')
        <li class="{{ pcrsMenuActiveCondition('konfigurasicontroller_index', $collection->get('activeMenu')) }}">
          <a href="{{ route('konfigurasi') }}">
            <i class="fa fa-fw fa-gear"></i> <span>Konfigurasi</span>
          </a>
        </li>
        @endcan
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
