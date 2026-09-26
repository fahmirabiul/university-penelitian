<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold ms-2">SIMLITAB</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item">
      <a href="{{ url('/') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    @auth
      @if(auth()->user()->role_lokal == 'dosen' || auth()->user()->role_lokal == 'ketua_peneliti')
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Menu Peneliti</span>
      </li>
      <li class="menu-item">
        <a href="{{ url('/dosen/penelitian') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-file"></i>
          <div data-i18n="Usulan Penelitian">Usulan Penelitian</div>
        </a>
      </li>
      @endif

      @if(auth()->user()->role_lokal == 'dosen' || auth()->user()->role_lokal == 'ketua_peneliti')
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Menu Reviewer</span>
      </li>
      <li class="menu-item">
        <a href="{{ route('reviewer.penilaian.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-check-shield"></i>
          <div data-i18n="Penugasan Review">Penugasan Review</div>
        </a>
      </li>
      @endif

      @if(auth()->user()->role_lokal == 'admin_lembaga')
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Menu LPPM (Admin)</span>
      </li>
      <li class="menu-item">
        <a href="{{ route('admin.penelitian.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-data"></i>
          <div data-i18n="Semua Proposal">Semua Proposal</div>
        </a>
      </li>
      @endif
    @endauth
  </ul>
</aside>
