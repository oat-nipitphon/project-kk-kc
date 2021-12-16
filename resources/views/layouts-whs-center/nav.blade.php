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
            <li class="{{ route::is('whs-center.dashboard') ? 'active' : '' }}">
                <a href="{{ route('whs-center.dashboard') }}"><i class="fa fa-diamond"></i> <span class="nav-label">กระดานสรุป</span></a>
            </li>
            <li class="{{ route::is('whs-center.pos*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">ใบขอซื้อสินค้า</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs-center.pos.select-pr') || route::is('whs-center.pos.select-vendor') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.pos.select-pr') }}">ใบขอซื้อสินค้าที่รอการสั่งซื้อ</a>
                    </li>
                </ul>
            </li>
            <li class="{{ route::is('whs-center.goods.set-check-goods.*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">จัดการสินค้า</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs-center.goods.set-check-goods.*') || route::is('whs-center.goods.set-check-goods.*') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.goods.set-check-goods.index') }}">ตั้งค่าตรวจสอบสินค้า</a>
                    </li>
                    <li class="{{ route::is('whs-center.goods.set-price-goods.*') || route::is('whs-center.goods.set-price-goods.*') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.goods.set-price-goods.index') }}">ตั้งค่าราคาสินค้า</a>
                    </li>
                    <li class="{{ route::is('whs-center.goods.set-ratio-goods.*') || route::is('whs-center.goods.set-ratio-goods.*') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.goods.set-ratio-goods.index') }}">ตั้งค่าแต้มสินค้า</a>
                    </li>
                </ul>
            </li>
            <li class="{{ route::is('whs-center.members.*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">จัดการสมาชิก</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ route::is('whs-center.members.set-members.*') || route::is('whs-center.members.set-members.*') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.members.set-members.index') }}">ตั้งค่าสมาชิก</a>
                    </li>
                    <li class="{{ route::is('whs-center.members.set-member-types.*') || route::is('whs-center.members.set-member-types.*') ? 'active' : '' }}">
                        <a href="{{ route('whs-center.members.set-member-types.index') }}">ตั้งค่าประเภทกลุ่มสมาชิก</a>
                    </li>
                </ul>
            </li>

            <li class="">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">รายงานจำนวนสินค้า</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="">
                        <a href="{{ route('whs-center.report.amount-goods.index') }}">สินค้าคงเหลือ</a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>
</nav>
