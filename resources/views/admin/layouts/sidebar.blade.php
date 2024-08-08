<!-- Brand Logo -->
<a href="{{route('admin.dashboard')}}" class="brand-link" style="height:57px;">
    <img src="{{asset('assets/images/favicon_white.png')}}" class="brand-image" style="opacity: .8">
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="{{asset('assets/admin/dist/img/user.jpg')}}" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
        <a href="javascript::void(0)" class="d-block">{{Auth::user()->name}}</a>
    </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
        <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
        </button>
        </div>
    </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{route('admin.dashboard')}}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                        Beranda
                    </p>
                </a>
            </li>
            <li class="nav-header">CONTENT</li>
            <li class="nav-item">
                <a href="{{route('banner.index')}}" class="nav-link {{ request()->routeIs('banner.*') ? 'active' : '' }}">
                    <i class="nav-icon far fa-image"></i>
                    <p>
                        Banner
                    </p>
                </a>
            </li>
            <li class="nav-header">MASTER DATA</li>
            <li class="nav-item">
                <a href="{{route('category.index')}}" class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list-alt"></i>
                    <p>
                        Category
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('composer.index')}}" class="nav-link {{ request()->routeIs('composer.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file"></i>
                    <p>
                        Composer
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('collection.index')}}" class="nav-link {{ request()->routeIs('collection.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        Collection
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('sheet-music.index')}}" class="nav-link {{ request()->routeIs('sheet-music.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Sheet Music
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('merchandise.index')}}" class="nav-link {{ request()->routeIs('merchandise.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-store"></i>
                    <p>
                        Merchandise
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.index')}}" class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                        Customer
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('voucher.index')}}" class="nav-link {{ request()->routeIs('voucher.*') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-percent"></i>
                    <p>
                        Voucher
                    </p>
                </a>
            </li>
            <li class="nav-header">TRANSACTION</li>
            <li class="nav-item">
                <a href="{{route('order.index')}}" class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}">
                    <i class="nav-icon far fa-credit-card"></i>
                    <p>
                        Order
                    </p>
                </a>
            </li>
            <li class="nav-header">SETTING</li>
            <li class="nav-item {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-user"></i>
                    <p>
                        User <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'block' : 'none' }};">
                    <li class="nav-item">
                        <a href="{{route('role.index')}}" class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}">
                            <i class="far fa-circle"></i>
                            <p>&nbsp;Role</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('user.index')}}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <i class="far fa-circle "></i>
                            <p>&nbsp;User</p>
                        </a>
                    </li>
              </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->