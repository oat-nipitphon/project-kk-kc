@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
		<span class="pull-right">
			<a href="{{ route('whs.prs.create') }}" class="btn btn-primary">
				<i class="fa fa-edit"></i>
				สร้างใบขอซื้อ
			</a>
		</span>
                <h3>ใบขอซื้อสินค้า</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>รหัสเอกสาร</th>
                        <th>วันที่</th>
                        <th>คลัง</th>
                        <th>ผู้บันทึก</th>
                        <th>สถานะ</th>
                        <th>ชุดคำสั่ง</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prs as $pr)
                        <tr>
                            <td>{{ $pr->code }}</td>
                            <td>{{ $pr->document_at->format('d/m/Y') }}</td>
                            <td>{{ $pr->warehouse->name }}</td>
                            <td>{{ $pr->createdUser->username }}</td>
                            <td>
                                @if($pr->approve_at == null)
                                    <span class="text-warning">รออนุมัติ</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('whs.prs.show', $pr->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</a>
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
