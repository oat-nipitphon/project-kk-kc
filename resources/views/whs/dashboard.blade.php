@extends('layouts-whs.app')

@section('content')
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Monthly</span>
                    <h5>Views</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">386,200</h1>
                    <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                    <small>Total views</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">Annual</span>
                    <h5>Orders</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">80,800</h1>
                    <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                    <small>New orders</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">Today</span>
                    <h5>visits</h5>
                </div>
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="no-margins">406,42</h1>
                            <div class="font-bold text-navy">44% <i class="fa fa-level-up"></i> <small>Rapid pace</small></div>
                        </div>
                        <div class="col-md-6">
                            <h1 class="no-margins">206,12</h1>
                            <div class="font-bold text-navy">22% <i class="fa fa-level-up"></i> <small>Slow pace</small></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Monthly income</h5>
                    <div class="ibox-tools">
                        <span class="label label-primary">Updated 12.2015</span>
                    </div>
                </div>
                <div class="ibox-content no-padding">
                    <div class="flot-chart m-t-lg" style="height: 55px;">
                        <div class="flot-chart-content" id="flot-chart1"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <form class="ibox" action="{{ route('whs.prs.create') }}" method="get">
                <div class="ibox-title">
                    <h5>Messages</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content ibox-heading">
                    <h3><i class="fa fa-envelope-o"></i> รายงานแจ้งเตือน Stock</h3>
                </div>
                <div class="ibox-content">
                    <div class="feed-activity-list">
                        @foreach($goodAudits as $goodAudit)
                            <div class="feed-element">
                                <div>
                                    @if ($goodAudit->current_amount < $goodAudit->min)
                                        <input type="checkbox" id="checkbox" name="good_ids[]" value="{{ $goodAudit->good_id }}">
                                        <strong class="text-danger">Stock น้อยกว่าที่ตั้งไว้</strong>
                                    @else
                                        <strong class="text-warning">Stock มากกว่าที่ตั้งไว้</strong>
                                    @endif
                                    <div>
                                        {{ $goodAudit->message }}
                                    </div>
                                    <small class="text-muted">{{ $goodAudit->created_at->format('d.m.Y') }}</small>
                                    @if ($goodAudit->prGoods->count() != 0)
                                        <span class="text-success">ขอซื้อไปแล้ว จำนวน {{ $goodAudit->prGoods->sum('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="ibox-footer">
                    <button type="submit" class="btn btn-primary" id="chooseGoodButton">สร้างใบขอซื้อ</button>
                </div>
            </form>
        </div>
@endsection
