<div class="sidebar">
    <div class="sidebar-header">
        <div class="app-icon">
            <img src="{{ asset('img/Scuto-logo.svg') }}" alt="Scuto Logo" class="app-logo-svg">
            <span class="app-name-text">Scuto Asset</span>
        </div>
        <button id="burger-menu" class="burger-button" title="Toggle Sidebar">
            <span></span><span></span><span></span>
        </button>
    </div>
    <ul class="sidebar-list">
        <li class="sidebar-list-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <a href="{{ route('dashboard.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" /></svg>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-list-item {{ request()->routeIs('surat.index') ? 'active' : '' }}">
            <a href="{{ route('surat.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                <span>Serah Terima</span>
            </a>
        </li>
        <li class="sidebar-list-item {{ request()->routeIs('companies.index') ? 'active' : '' }}">
            <a href="{{ route('companies.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                <span>Data Master</span>
            </a>
        </li>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
        <li class="sidebar-list-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                <span>Manage User</span>
            </a>
        </li>
        @endif
    </ul>
    <div class="account-info">
        <div class="account-info-picture">
            <img src="{{ asset('img/Logo-scuto.png') }}" alt="Account">
        </div>
        <div class="account-info-name">{{ Auth::user()->name }}</div>
    </div>
</div>