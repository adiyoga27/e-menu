<style>
    #sidebar-wrapper {
        min-height: 100vh;
        width: 250px;
        transition: margin .25s ease-out;
        z-index: 1000;
        background-color: #fff;
        border-right: 1px solid #dee2e6;
    }

    #sidebar-wrapper .sidebar-heading {
        padding: 1.5rem 1.25rem;
        font-size: 1.2rem;
        border-bottom: 1px solid #eee;
    }

    #sidebar-wrapper .list-group {
        width: 250px;
    }

    #sidebar-wrapper .list-group-item {
        padding: 0.75rem 1.25rem;
        border: none;
        color: #495057;
        font-weight: 500;
        transition: background-color 0.2s, color 0.2s;
    }

    #sidebar-wrapper .list-group-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    #sidebar-wrapper .list-group-item.active {
        background-color: #e7f1ff;
        color: #0d6efd;
        border-right: 4px solid #0d6efd;
    }

    @media (max-width: 768px) {
        #sidebar-wrapper {
            margin-left: -250px;
        }
        body.sb-sidenav-toggled #sidebar-wrapper {
            margin-left: 0;
        }
    }
</style>

<div id="sidebar-wrapper">
    <div class="sidebar-heading d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center text-dark">
            <x-application-logo style="height: 32px; width: auto;" class="me-2 text-primary" />
            <span class="fw-bold">Admin Panel</span>
        </a>
    </div>
    
    <div class="list-group list-group-flush mt-3">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> {{ __('Dashboard') }}
        </a>
        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid me-2"></i> Kategori
        </a>
        <a href="{{ route('admin.menus.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text me-2"></i> Menu
        </a>
        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action {{ (request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show')) ? 'active' : '' }}">
            <i class="bi bi-cart me-2"></i> Pesanan
        </a>
        <a href="{{ route('admin.orders.report') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.orders.report') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan
        </a>
    </div>

</div>
