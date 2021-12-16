<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="/img/profile_small.jpg" />
                             </span>
                    <a href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ auth()->user()->name }}</strong>
                             </span> <span class="text-muted text-xs block">KC Employee </span> </span> </a>
                </div>
                <div class="logo-element">
                    KC+
                </div>
            </li>
            <li>
                <a href="{{ route('select-program') }}"><i class="fa fa-diamond"></i> <span class="nav-label">กลับหน้าเลือกโปรแกรม</span></a>
            </li>
            <li class="{{ route::is('whs.dashboard') ? 'active' : '' }}">
                <a href="{{ route('whs.dashboard') }}"><i class="fa fa-diamond"></i> <span class="nav-label">กระดานสรุป</span></a>
            </li>
            <li class="{{ route::is('whs.prs*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">ใบขอซื้อสินค้า</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs.prs.*') ? 'active' : '' }}">
                        <a href="{{ route('whs.prs.index') }}">ใบขอซื้อสินค้าทั้งหมด</a>
                    </li>
                    <li class="{{ route::is('whs.prs-approve.*') ? 'active' : ''}}">
                        <a href="{{ route('whs.prs-approve.index') }}">อนุมัติใบขอซื้อสินค้า</a>
                    </li>
                    <li class="{{ route::is('whs.prs-report.*') ? 'active' : ''}}">
                        <a href="{{ route('whs.prs-report.index') }}">รายงานใบขอซื้อสินค้า</a>
                    </li>
                </ul>
            </li>
            <li class="{{ route::is('whs.withdraw-red-label.*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">เบิกทำป้ายแดง</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs.withdraw-red-label.*') && !route::is('whs.withdraw-red-label.approve.*') ? 'active' : '' }}">
                        <a href="{{ route('whs.withdraw-red-label.index') }}">เบิกทำป้ายแดงทั้งหมด</a>
                    </li>
                    <li class="{{ route::is('whs.withdraw-red-label.approve.*') ? 'active' : '' }}">
                        <a href="{{ route('whs.withdraw-red-label.approve.index') }}">รออนุมัติเบิกทำป้ายแดง</a>
                    </li>
                </ul>
            </li>
            <li class="{{ route::is('whs.requisitions.*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">เบิกสินค้า</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs.requisitions.*') && !route::is('whs.requisitions.approve.*') ? 'active' : '' }}">
                        <a href="{{ route('whs.requisitions.index') }}">ใบเบิกทั้งหมด</a>
                    </li>
                    <li class="{{ route::is('whs.requisitions.approve.*') ? 'active' : '' }}">
                        <a href="{{ route('whs.requisitions.approve.index') }}">รออนุมัติเบิกสินค้า</a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
</nav>
