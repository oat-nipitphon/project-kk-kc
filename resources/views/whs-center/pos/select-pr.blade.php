@extends('layouts-whs-center.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <h3>ใบขอซื้อสินค้าที่รอการสั่งซื้อ</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>วันที่ใบขอซื้อ</th>
                        <th>เลขที่ใบขอซื้อ</th>
                        <th>คลัง</th>
                        <th>สถานะ</th>
                        <th>เลือก</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prs as $pr)
                        <tr>
                            <td>{{ $pr->id }}</td>
                            <td>{{ $pr->created_at->format('d/m/Y') }}</td>
                            <td>{{ $pr->code }}</td>
                            <td>{{ $pr->warehouse->name }}</td>
                            <td>
                                @if($pr->status == 0)
                                    <span class="label label-primary">ยังไม่สั่งซื้อ</span>
                                @elseif ($pr->status == 1)
                                    <span class="label label-warning">สั่งซื้อไปแล้วบางส่วน</span>
                                @elseif ($pr->status == 2)
                                    <span class="label label-success">สั่งซื้อครบแล้ว</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('whs-center.pos.select-vendor', ['pr_id' => $pr->id]) }}" class="btn btn-info btn-xs {{ ($pr->status == 2 ? 'hidden' : '') }}">
                                    <i class="fa fa-hand-o-up" aria-hidden="true"></i> เลือก
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/js/plugins/dataTables/datatables.min.js"></script>

    <script type="text/javascript">
        $(function(){
            $('.table').dataTable();
        });
    </script>
@stop
