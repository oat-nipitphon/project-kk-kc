@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <h3>อนุมัติใบเบิกทำป้ายแดง</h3>
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
                                @if($withdrawRedLabel->approve_at == null)
                                    <span class="text-warning">รออนุมัติ</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('whs.withdraw-red-label.approve.show', $withdrawRedLabel->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</a>
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
