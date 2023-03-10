<aside class="main-sidebar sidebar-dark-warning elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('vendor/admin-lte/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light fs-5">Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/images/' . Auth::user()->images) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('my.profile.index') }}" class="d-block">{{ Auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

               {{-- Home --}}
               <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ Request::is('dashboard', 'my-profile') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                {{-- All Users --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('users') }}" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Users Info
                        </p>
                    </a>
                </li>  --}}

                {{-- Users --}}
                @if (Auth()->user()->role == "superAdmin")
                    <li class="nav-item has-treeview {{ Request::is('dashboard/user*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('dashboard/user*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                Members
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}" class="nav-link {{ Request::is('dashboard/user') ? 'active' : '' }}">
                                <i class="fa fa-list nav-icon ms-3"></i>
                                <p>List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.create') }}" class="nav-link {{ Request::is('dashboard/user/create') ? 'active' : '' }}">
                                <i class="fa fa-file-signature nav-icon  ms-3"></i>
                                <p>Create</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Tag --}}
                <li class="nav-item has-treeview {{ Request::is('dashboard/tag*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('dashboard/tag*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-tags"></i>
                        <p>
                            Tags
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tag.index') }}" class="nav-link {{ Request::is('dashboard/tag') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon ms-3"></i>
                            <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tag.create') }}" class="nav-link {{ Request::is('dashboard/tag/create') ? 'active' : '' }}">
                            <i class="fa fa-file-signature nav-icon  ms-3"></i>
                            <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Category --}}
                <li class="nav-item has-treeview {{ Request::is('dashboard/category*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('dashboard/category*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-layer-group"></i>
                        <p>
                            Categories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link {{ Request::is('dashboard/category') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon ms-3"></i>
                            <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.create') }}" class="nav-link {{ Request::is('dashboard/category/create') ? 'active' : '' }}">
                            <i class="fa fa-file-signature nav-icon ms-3"></i>
                            <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Post --}}
                <li class="nav-item has-treeview {{ Request::is('dashboard/post*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('dashboard/post*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-newspaper"></i>
                        <p>
                            Posts
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('post.index') }}" class="nav-link {{ Request::is('dashboard/post') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon ms-3"></i>
                            <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('post.create') }}" class="nav-link {{ Request::is('dashboard/post/create') ? 'active' : '' }}">
                            <i class="fa fa-file-signature nav-icon ms-3"></i>
                            <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
    
</aside>