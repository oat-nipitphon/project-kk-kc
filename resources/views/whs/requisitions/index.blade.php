@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
		<span class="pull-right">
			<a href="{{ route('whs.requisitions.create') }}" class="btn btn-primary">
				<i class="fa fa-edit"></i>
				สร้างใบเบิกสินค้า
			</a>
		</span>
                <h3>ใบเบิกสินค้า</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>คลังสินค้าที่เบิก</th>
                        <th>เลขที่เอกสาร</th>
                        <th>วันที่เอกสาร</th>
                        <th>วัตถุประสงค์การเบิก</th>
                        <th>ผู้บันทึก</th>
                        <th>ผู้อนุมัติ</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requisitions as $requisition)
                        <tr>
                            <td>{{ $requisition->warehouse->name }}</td>
                            <td>{{ $requisition->code }}</td>
                            <td>{{ $requisition->document_at->format('d/m/Y') }}</td>
                            <td>{{ $requisition->take->name }}</td>
                            <td>
                                @if($requisition->created_user_id == 1)
                                    <span class="label label-primary">Admin</span>
                                @else
                                    <span class="label label-primary">{{ $requisition->createUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                @if($requisition->approve_user_id == 0 && $requisition->none_approve_user_id == 0)
                                    <span class="label label-warning">รอการตรวจสอบ</span>
                                @elseif($requisition->approve_user_id == 1)
                                    <span class="label label-primary">Admin</span>
                                @elseif($requisition->approve_user_id > 1)
                                    <span class="label label-primary">{{ $requisition->approveUser->username }}</span>
                                @elseif($requisition->none_approve_user_id == 1)
                                    <span class="label label-danger">ไม่อนุมัติ/Admin</span>
                                @elseif($requisition->none_approve_user_id > 1)
                                    <span
                                        class="label label-danger">ไม่อนุมัติ/{{ $requisition->noneApproveUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('whs.requisitions.show', $requisition->id) }}"
                                   class="btn btn-info btn-xs">แสดงรายละเอียด</a>
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
            });
        });
    </script>
@stop
