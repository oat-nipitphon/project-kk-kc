@extends('layouts-whs-center.app')

@section('css')
{{-- <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet"> --}}
@stop

@section('content')
<div class="row">
    <div class="ibox">
        <div class="ibox-title">
            <span class="pull-right">
                <span style="color:#ff1a1a;">** สินค้าที่ไม่ได้ตั้งค่าแต้มจะใช้ </span><span style="color:#ff1a1a;" id="baseRatio"> </span><span style="color:#ff1a1a;">  บาท : 1 แต้ม ** </span>
                <button type="button" class="btn btn-primary" onclick="editBaseRatio()">
                    <i class="fa fa-pencil"></i> แก้ไข
                </button>
                <button type="button" id="addRatioGoodBtn" class="btn btn-primary">
                    <i class="fa fa-search-plus"></i>เพิ่มสินค้าที่จะตั้งค่าแต้ม
                </button>
            </span>
            <h3>สินค้าที่ตั้งค่าแต้ม</h3>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered" id="good_ratio_table" style="width : 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รหัสสินค้า</th>
                        <th>ประเภทสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>หน่วย</th>
                        <th>บาท : แต้ม</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="goodModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">ค้นหาสินค้า</h4>
            </div>
            <form action="{{ route('whs-center.goods.set-ratio-goods.checkOutGood') }}" id="addRatioGoodForm"
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
                    <button type="button" id="addRatioGoodSubmitBtn" class="btn btn-primary">
                        เพิ่มสินค้าที่จะตั้งค่าแต้ม
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
$("#addRatioGoodBtn").click(function () {
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
        "pageLength": 10,
        "order": [
            [0, "asc"]
        ],
        "ajax": {
            "url": "/whs-center/goods/set-ratio-goods/showGoodModal",
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
        }, ],
        "columns": [{
                "data": "id",
                "render": function (data) {
                    return `<td><input type="checkbox" name="id[]" value="${data}"></td>`;
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

let good_ratio_table = '';
$("#good_ratio_table").ready(function () {
    good_ratio_table = $('#good_ratio_table').DataTable({
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
            "url": "/whs-center/goods/set-ratio-goods",
            "method": "POST",
            "data": {
                "_token": "{{ csrf_token()}}",
            },
        },
        'columnDefs': [{
                "targets": 0,
                visible: false,
                "searchable": false,
                "className": "text-center",
            },
            {
                "targets": [4, 5, 6],
                "className": "text-center",
            },
        ],
        "columns": [{
                "data": "good_id",
            },
            {
                "data": "good.code",
            },
            {
                "data": "good.type.name",
            },
            {
                "data": "good.name",
            },
            {
                "data": "good.unit.name",
            },
            {
                "data": "ratio",
                "render": function (data) {
                    let html = '';
                    let ratio = '';
                    if (data == 0 || data == '0') {
                        html = `<p> ไม่มีแต้ม </p>`;
                    } else {
                        ratio = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        html = `<p style="color: #468847"><b>${ratio} : 1 </b></p>`;
                    }
                    return html;
                }
            },
            {
                "data": "good_id",
                "render": function (data, type, full) {

                    return `<button class="btn btn-warning btn-sm" onclick="editRatioBtn(${full.good.id},'${full.good.code}',${full.ratio})"><i class="fa fa-pencil"></i> แก้ไข</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteRatioBtn(${data})"><i class="fa fa-minus"></i> ลบ</button>
                                `;
                }
            },
        ],
    });

});


function editRatioBtn(id, code, ratio) {
    console.log(code);
    Swal.fire({
        title: "แก้ไขแต้มสินค้า [บาท : แต้ม] ",
        text: "รหัสสินค้า : " + code,
        input: 'number',
        inputValue: ratio,
        showCancelButton: true
    }).then((result) => {
        if (result.value) {
            console.log("Result: " + result.value);
            $.post("/whs-center/goods/set-ratio-goods/storeGoodRatio", data = {
                    _token: '{{ csrf_token() }}',
                    good_id: id,
                    good_ratio: result.value,
                },
                function (res) {
                    console.log('Store SUCCESS!!');
                    (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                    good_ratio_table.ajax.reload(null, false);
                },
            );
        }
    });
}


function deleteRatioBtn(id) {
    Swal.fire({
        title: 'คุณมั่นใจหรือไม่ ?',
        text: "คุณค้องการลบการตั้งค่าแต้มสินค้านี้ใช่หรือไม่",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
    }).then((result) => {
        if (result.value) {
            $.post("/whs-center/goods/set-ratio-goods/deleteGoodRatio", data = {
                    _token: '{{ csrf_token() }}',
                    good_id: id,
                },
                function (res) {
                    (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                    good_ratio_table.ajax.reload(null, false);
                },
            );
        }
    });
}

$("#addRatioGoodSubmitBtn").click(function () {
    $(this).attr('disabled', true);
    $(this).val("รอสักครู่...");
    $("#addRatioGoodForm").submit();
});

$("#baseRatio").ready(function () {
     $.post("/whs-center/goods/set-ratio-goods/showBaseRatio", data = {
                _token: '{{ csrf_token() }}',
            },
            function (res) {
                $("#baseRatio").text(res);
            }
        );
});

function editBaseRatio() {
    $.post("/whs-center/goods/set-ratio-goods/showBaseRatio", data = {
                _token: '{{ csrf_token() }}',
            },
            function (res) {
                ratio = res;
                Swal.fire({
                title: "แก้ไขแต้มมาตรฐานสินค้า [บาท : แต้ม] ",
                input: 'number',
                inputValue: ratio,
                showCancelButton: true
            }).then((result) => {
                if (result.value) {
                    console.log("Result: " + result.value);
                    $.post("/whs-center/goods/set-ratio-goods/storeBaseRatio", data = {
                            _token: '{{ csrf_token() }}',
                            ratio: result.value,
                        },
                        function (res) {
                            console.log('Store SUCCESS!!');
                            (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                            location.reload();  
                        },
                    );
                }
            }); 
            }
    );  
}

</script>
@stop



