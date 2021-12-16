@extends('layouts-whs-center.app')

@section('content')
<div class="row">
    <div class="ibox">
        <div class="ibox-title">
            <span class="pull-right">
                <button type="button" id="addPriceGoodBtn" class="btn btn-primary">
                    <i class="fa fa-search-plus"></i>เพิ่มสินค้าที่จะตั้งค่าราคา
                </button>
            </span>
            <h3>สินค้าที่จะตั้งค่าราคา</h3>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered" id="good_price_table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รหัสสินค้า</th>
                        <th>ประเภทสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>หน่วย</th>
                        <th>สร้างเมื่อ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!--Good Modal -->
<div class="modal fade" id="goodModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">ค้นหาสินค้า</h4>
            </div>
            <form action="{{ route('whs-center.goods.set-price-goods.checkOutGood') }}" id="addPriceGoodForm"
                method="POST" class="form-horizontal">
                @csrf
                <div class="modal-body" style="padding:20px;">
                    <table class="table table-bordered" id="good_table" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>รหัสสินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>หน่วยนับ</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addPriceGoodSubmitBtn" class="btn btn-primary">
                        เพิ่มสินค้าที่จะตั้งค่าราคา
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')

<script>
    $("#addPriceGoodBtn").click(function () {
        $('#goodModal').modal("show");
        //Good table on Modal
        let good_table = $('#good_table').DataTable({
            "processing": false,
            "serverSide": true,
            "info": false,
            "searching": true,
            "responsive": true,
            "bFilter": false,
            "bLengthChange": true,
            "destroy": true,
            "pageLength": 6,
            "order": [
                [0, "asc"]
            ],
            "ajax": {
                "url": "/whs-center/goods/set-price-goods/showGoodModal",
                "method": "POST",
                "data": {
                    "_token": "{{ csrf_token()}}",
                },
            },
            'columnDefs': [{
                    "targets": 1,
                    visible: false,
                    "searchable": false,
                    "className": "text-center",
                },
                {
                    "targets": 0,
                    "className": "text-center",
                },
            ],
            "columns": [{
                    "data": "id",
                    "render": function (data) {
                        return `<td><input type="checkbox" name="id[]" value="${data}" class="form-control"></td>`;
                    }
                },
                {
                    "data": "id",
                },
                {
                    "data": "code",
                },
                {
                    "data": "type.name",
                },
                {
                    "data": "name",
                },
                {
                    "data": "unit.name",
                },
            ],

        });
    });

    let good_price_table = '';
    $("#good_price_table").ready(function () {
        good_price_table = $('#good_price_table').DataTable({
            "processing": false,
            "serverSide": true,
            "info": false,
            "searching": true,
            "responsive": true,
            "bFilter": false,
            "bLengthChange": true,
            "destroy": true,
            "pageLength": 10,
            "order": [
                [0, "asc"]
            ],
            "ajax": {
                "url": "/whs-center/goods/set-price-goods",
                "method": "POST",
                "data": {
                    "_token": "{{ csrf_token()}}",
                },
            },
            'columnDefs': [{
                    "targets": 0,
                    visible: false,
                    "searchable": false,
                },
                {
                    "targets": [5, 6],
                    "className": "text-center",
                },
            ],
            "columns": [{
                    "data": "id",
                },
                {
                    "data": "code",
                },
                {
                    "data": "type.name",
                },
                {
                    "data": "name",
                },
                {
                    "data": "unit.name",
                },
                {
                    "data": "updated_at",
                    "render": function (data) {
                        return moment(data).format('DD MMMM YYYY');
                    }
                },
                {
                    "data": "id",
                    "render": function (data, type, full) {
                        return `<a class="btn btn-info btn-sm" href="set-price-goods/${data}"> ดูรายละเอียด</a>
                        <button class="btn btn-danger btn-sm " onclick="deleteGoodBtn(${data})" class="form-control"><i class="fa fa-minus"></i> ลบ</button>
                        `;
                    }
                },
            ],
        });

    });

    function deleteGoodBtn(id) {
        Swal.fire({
            title: 'คุณมั่นใจหรือไม่ ?',
            text: "คุณค้องการลบการตั้งค่าราคาสินค้านี้ใช่หรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.value) {
                $.post("/whs-center/goods/set-price-goods/deleteGood", data = {
                        _token: '{{ csrf_token() }}',
                        good_id: id,
                    },
                    function (res) {
                        (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                        good_price_table.ajax.reload(null, false);
                    },
                );
            }
        });
    }

    $("#addPriceGoodSubmitBtn").click(function () {
        $(this).attr('disabled', true);
        $(this).val("รอสักครู่...");
        $("#addPriceGoodForm").submit();
    });

</script>
@stop



