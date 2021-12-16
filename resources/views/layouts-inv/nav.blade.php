<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="/img/profile_small.jpg" />
                             </span>
                    <a href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ auth()->user()->name }}</strong>
                             </span> <span class="text-muted text-xs block">KC INV</span> </span> </a>
                </div>
                <div class="logo-element">
                    KC+
                </div>
            </li>
            <li>
                <a href="{{ route('select-program') }}"><i class="fa fa-diamond"></i> <span class="nav-label">กลับหน้าเลือกโปรแกรม</span></a>
            </li>

            <li>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">เบิกสินค้า</span></a>
                <ul class="nav nav-second-level">
                    {{-- ดูเพิ่มเติม li class--}}
                <li class="{{ route::is('kc-inv.requisitions') ? 'active' : '' }}">
                    <a href="{{ route('kc-inv.requisitions') }}">ใบเบิกทั้งหมด</a>
                  </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
