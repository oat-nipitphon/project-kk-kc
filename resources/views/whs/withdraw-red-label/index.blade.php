@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
		<span class="pull-right">
			<a href="{{ route('whs.withdraw-red-label.create') }}" class="btn btn-primary">
				<i class="fa fa-edit"></i>
				สร้างใบเบิกทำป้ายแดง
			</a>
		</span>
                <h3>ใบเบิกทำป้ายแดง</h3>
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
                    @foreach($withdrawRedLabels as $withdrawRedLabel)
                        <tr>
                            <td>{{ $withdrawRedLabel->code }}</td>
                            <td>{{ $withdrawRedLabel->document_at->format('d/m/Y') }}</td>
                            <td>{{ $withdrawRedLabel->warehouse->name }}</td>
                            <td>{{ $withdrawRedLabel->createdUser->username }}</td>
                            <td>
                                @if($withdrawRedLabel->approve_at == null && $withdrawRedLabel->approve_user_id == 0 && $withdrawRedLabel->none_approve_user_id == 0)
                                    <span class="text-warning">รออนุมัติ</span>
                                @elseif($withdrawRedLabel->none_approve_user_id != 0)
                                    <span class="text-danger">ไม่อนุมัติ</span>
                                @elseif($withdrawRedLabel->approve_user_id != 0)
                                    <span class="text-success">อนุมัติแล้ว</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('whs.withdraw-red-label.show', $withdrawRedLabel->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</a>
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
            $('.table').dataTable({
                pageLength: 25,
                responsive: true,
                order: [[ 4, "asc" ]],
            });
        });
    </script>
@stop
