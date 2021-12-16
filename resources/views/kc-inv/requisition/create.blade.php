@extends('layouts-inv.app')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>รายการใบเบิกสินค้าที่สร้าง</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong><a href="{{ route('select-program') }}">หน้าหลัก</strong>
                /
                <strong><a href="{{ route('kc-inv.requisitions') }}">รายการใบเบิกสินค้าที่สร้าง</a></strong>
            </li>
        </ol>
    </div>

    <div class="ibox row wrapper border-bottom white-bg page-heading">
        <div class="ibox-title">
            <h3>สร้างใบเบิกสินค้า</h3>
        </div>
        <div class="ibox-content">
            <div class="row row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <span class="pull-right">เลขที่เอกสาร xxx-xxx-xxx</span>
                </div>
                <div class="col-lg-3">
                    <label for="">วันที่เอกสาร</label>
                    <div class="input-group">
                        <div class="fg-line">
                            <input class="form-control" data-type="date" name="document_at" type="text" value="22/07/2020" readonly>
                        </div>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="wh_id">เลือกประเภทการเบิก</label>
                    <select class="form-control" id="take" name="take_id"><option selected="selected" value="">เลือกประเภทการเบิก...</option><option value="1">ขอเบิกอื่นๆ</option><option value="2">ขอเบิกใช้</option><option value="3">ขอเบิกตัวอย่าง</option><option value="4">ขอเบิกตัดชำรุด</option><option value="5">ขอเบิกยืม</option><option value="6">เบิกทำป้ายแดง</option></select>
                </div>
                <div class="col-lg-12">
                    <br>
                    <label for="">หมายเหตุ</label>
                        <input class="form-control" name="detail" type="text">
                </div>
            </div>
        </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 80%">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="row">
                        @foreach ($types as $type)
                            <div class="col-lg-3 col-md-3 col-sm-3" style="margin-bottom:10px;">
                                <button type="button" class="btn btn-info btn-block typeSelect" onclick="tables({{ $type->id }})">{{ $type->name }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

                <div id="wrapper">
                    <div class="col-lg-12">
                        <form id="inv-submit" action="{{ route('kc-inv.config.goods') }}" method="POST">
                            @csrf
                        <div class="table-responsive" style="margin-top:25px;">
                            <table class="table table-sm table-striped" id="modal-table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">รหัสสินค้า</th>
                                        <th class="text-center">ชื่อสินค้า</th>
                                        <th class="text-center">คงเหลือ</th>
                                        <th class="text-center">หน่วยนับ</th>
                                        <th class="text-center">######</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-primary" ><i class="fa fa-check"></i>&nbsp;เลือก</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </form>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;ยกเลิก</button>
                            <br><br><br>
                        </span>
                        <br><br><br>
                    </div>
                    <br><br><br>
                </div>

            </div>
        </div>
    </div>
    <span class="pull-right">
        <p id="show-btn">
            <button type="button" style="margin-bottom:10px;" id="btn-config" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                เพิ่มสินค้า
            </button>
        </p>
    </span>
        <form action="{{ route('kc-inv.config.store') }}" method="POST">
            @csrf
            <table border="1" style="width: 100%;" id="inv-detail">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10%">รหัสสินค้า</th>
                        <th class="text-center" style="width: 30%">ชื่อสินค้า</th>
                        <th class="text-center" style="width: 10%">จำนวน</th>
                        <th class="text-center" style="width: 10%">หน่วยนับ</th>
                        <th class="text-center" style="width: 20%">จำนวนคงเหลือในคลัง</th>
                        <th class="text-center" style="width: 20%">########</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table><br><br><center>
            <button class="btn btn-primary" ><i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;บันทึก</button>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;ยกเลิก</button>
            </center>
        </form>
</div>
@endsection

@section('script')
<script>

    delete_inv = (inv) => {
        $(`#${inv}`).remove();
    }

    $('#inv-submit').submit(function(e){
        e.preventDefault()
        let show_btn = $('#show-btn').val();
        let form = new FormData(this);
        let route = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: route,
            data: form,
            processData: false,
            contentType: false,
            success: function (res) {
                let data = res.data;
                let tables = $('#inv-detail')
                tables.find('tbody').empty();
                let i = 1;
                data.forEach(element =>  {
                    i++
                    tables.find('tbody').append(`
                        <tr id="inv-${i}">
                            <td class="text-center">${element.good.code}
                            <input type="hidden" name="good_code[]" value="${element.good.code}"></td>

                            <td class="text-center">${element.good.name}
                            <input type="hidden" name="good-name[]" value="${element.good.name}"></td>

                            <td class="text-center"><input type="text" name="add-balance[]"></td>

                            <td class="text-center">${element.good.unit.name}
                            <input type="hidden" name="unit[]" value="${element.good.unit.name}"></td>

                            <td class="text-center">${element.balance_amount}
                            <input type="hidden" name="balance-amount[]" value="${element.balance_amount}"></td>

                            <td class="text-center"><button style="margin-bottom:10px;" onclick="delete_inv('inv-${i}')"
                                class="btn btn-danger">Delete</button>
                                <input type="hidden" name="add-balance[]"></td>
                        </tr>
                        `)
                });
            }
        });
        move()
    });

    // Modal Move
    function move(){
        $('#exampleModal').modal('toggle');
    }

    // Table Modal
    var modal_table = $('#modal-table').DataTable();
    tables = (data) => {
        modal_table = $('#modal-table').DataTable({
        "ordering": true,
        "bPaginate": true,
        "searching": true,
        "info": false,
        "responsive": true,
        "bFilter": false,
        "bLengthChange": true,
        "destroy": true,
        "pageLength": 5,
        "ajax": {
            "url": "/kc-inv/type/list/goods",
            "method": "POST",
            "data": {
                "_token": "{{ csrf_token()}}",
                "type_id" : data
            },
        },
        "columns": [
            {
                "data": "warehouse_good_id",
            },
            {
                "data": "good.name",
            },
            {
                "data": "balance_amount",
            },
            {
                "data": "good.unit.name",
            },
            {
                "render": function(data,type,full){
                    return `<input type="checkbox" name="select[]" value="${full.warehouse_good_id}">`;
                }
            },
        ],

        });
    }

</script>
@endsection

