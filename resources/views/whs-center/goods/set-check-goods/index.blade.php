@extends('layouts-whs-center.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <span class="pull-right">
                    <button type="button" id="addCheckGoodButton" class="btn btn-primary">
                        <i class="fa fa-search-plus"></i>เพิ่มสินค้าที่จะตรวจสอบ
                    </button>
		        </span>
                <h3>สินค้าที่ตั้งค่าตรวจสอบ</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>ประเภทสินค้า</th>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($goods as $good)
                        <tr>
                            <td>{{ $good->id }}</td>
                            <td>{{ $good->type->name }}</td>
                            <td>{{ $good->code }}</td>
                            <td>{{ $good->name }}</td>
                            <td>
                                <a href="{{ route('whs-center.goods.set-check-goods.show', $good->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="goodModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">ค้นหาสินค้า</h4>
                </div>
                <form action="{{ route('whs-center.goods.set-check-goods.store') }}" method="POST" class="form-horizontal" id="addCheckGoodForm">
                    @csrf
                    <div class="modal-body" style="padding:20px;" id="dataGoods">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="addCheckGoodSubmitButton" class="btn btn-primary">
                            เพิ่มสินค้าที่จะตรวจสอบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/js/plugins/dataTables/datatables.min.js"></script>

    <script type="text/javascript">
        let good_ids = []
        @foreach($good_ids as $key => $good_id)
            good_ids[{{ $key }}] = {{ $good_id }}
        @endforeach
        $(function(){
            $('.table').dataTable();
        });

        $(document).on("click", "#addCheckGoodButton", function () {
            $('#dataGoods').empty();
            $.ajax({
                url: '/good-table',
                type: 'get',
                data: {
                  'good_ids': good_ids
                },
                success: function (response) {
                    $('#dataGoods').html(response.html);
                    $("#goodTable").dataTable();
                    $('#goodModal').modal('show');
                },
                error: function () {
                    swal("ติดต่อฐานข้อมูลไม่ได้ โปรดแจ้งเจ้าหน้าที่");
                }
            });
        });

        $(document).on("click", "#addCheckGoodSubmitButton", function () {
            $(this).attr('disabled', true);
            $(this).val("รอสักครู่...")
            $("#addCheckGoodForm").submit();
        });
    </script>
@stop
