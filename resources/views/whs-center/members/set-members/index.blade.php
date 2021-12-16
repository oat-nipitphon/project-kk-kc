@extends('layouts-whs-center.app')

@section('css')

@stop

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>ตั้งค่าสมาชิก</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('whs-center/dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <strong>ตั้งค่าสมาชิก</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            <button type="button" id="addMemberBtn" class="btn btn-primary">
                <i class="fa fa-search-plus"></i>เพิ่มสมาชิก
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-white"> ย้อนกลับ </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="ibox">
        <div class="ibox-title">
            <span class="pull-right">
            <form action=" {{ route('whs-center.members.set-members.exportExcel') }} " method="POST" id="exportExcelForm">
                @csrf
                <label>ประเภทกลุ่มสมาชิก</label>
                    <select name="memberTypeAll" id="memberTypeAll" form="exportExcelForm" required>

                    </select>
                    <label>สาขา</label>
                    <select name="warehouseAll" id="warehouseAll" form="exportExcelForm" required>

                    </select>
                    <label>เดือน</label>
                    <input type="month" value="{{date('Y-m')}}" name="month" required>
                    <button type="submit" class="btn btn-info">
                        <i class="fa fa-file-excel-o"></i> ExportExcel
                    </button>
                </form>
            </span>
            <h3>สมาชิก</h3>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered" id="member_table" style="width : 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รหัสลูกค้า</th>
                        <th>รหัสสมาชิก</th>
                        <th>ประเภทกลุ่มสมาชิก</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>เบอร์โทร</th>
                        <th>เริ่มเป็นสมาชิก</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">ค้นหาสินค้า</h4>
            </div>
            <form action="{{ route('whs-center.members.set-members.checkOutCustomer') }}" id="addMemberForm"
                method="POST" class="form-horizontal">
                @csrf
                <div class="modal-body" style="padding:20px;">
                    <table class="table table-bordered" id="customer_table" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>รหัสลูกค้า</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>เบอร์โทร</th>
                                <th>วันที่สร้าง</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addMemberSubmitBtn" class="btn btn-primary">
                        เพิ่มเป็นสมาชิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">เพิ่มสมาชิก</h4>
            </div>

            <div class="modal-body">

                <label><span style="color:red">*</span>รหัสสมาชิก </label>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" id="memberCode" name="memberCode" placeholder="รหัสสมาชิก" required>
                        <div class="btn-info input-group-addon" style="cursor:pointer" id="randomCode"> สุ่ม </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><span style="color:red">*</span>ประเภทกลุ่มสมาชิก <a href="{{ route('whs-center.members.set-member-types.index') }}"
                            class="badge badge-warning"> <i class="fa fa-plus"></i>
                            เพิ่มประเภทสมาชิก</a></label>
                    <select id="memberType" name="memberType" class="form-control" required>

                    </select>
                </div>

                <label>เลขประจำตัวประชาชน </label>
                <div class="form-group">
                    <input type="text" class="form-control" id="idCard" name="idCard" placeholder="เช่น 1-2345-67894-42-0" maxlength="17">
                </div>

                <label>ธนาคาร</label>
                <div class="form-group">
                    <select id="bankName" name="bankName" class="form-control" >
                        <option value="">กรุณาเลือก</option>
                        <option value="ธนาคารกรุงเทพ">ธนาคารกรุงเทพ</option>
                        <option value="ธนาคารกรุงไทย">ธนาคารกรุงไทย</option>
                        <option value="ธนาคารกรุงศรี">ธนาคารกรุงศรี</option>
                        <option value="ธนาคารทหารไทย">ธนาคารทหารไทย</option>
                        <option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย</option>
                        <option value="ธนาคารไทยพานิชย์">ธนาคารไทยพานิชย์</option>
                        <option value="ธนาคารยูโอบี">ธนาคารยูโอบี</option>
                        <option value="ธนาคารออมสิน">ธนาคารออมสิน</option>
                    </select>
                </div>

                <label>เลขบัญชีธนาคาร</label>
                <div class="form-group">
                    <input type="text" class="form-control" id="bankAccountNumber" name="bankAccountNumber" placeholder="เช่น 123-456-7890">
                </div>

                <div class="form-group">
                    <label>สถานะ : <span id="memberStatus"> </span></label>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                <button type="button" id="saveMemberBtn" class="btn btn-primary">บันทึก</button>
            </div>

        </div>
    </div>
</div>
<!-- End Modal -->

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/inputmask/bindings/inputmask.binding.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>

<script>

$("#idCard").inputmask("9-9999-99999-99-9");
$("#bankAccountNumber").inputmask("999-999-9999");

let member_id = '';
$("#addMemberBtn").click(function () {
    $('#customerModal').modal("show");
    //Good table on Modal
    let customer_table = $('#customer_table').DataTable({
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
            "url": "/whs-center/members/set-members/showCustomer",
            "method": "POST",
            "data": {
                "_token": "{{ csrf_token()}}",
            },
        },
        'columnDefs': [{
                "targets": 1,
                visible: true,
                "searchable": true,
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
                "data": "name",
            },
            {
                "data": "tel",
            },
            {
                "data": "created_at",
            },
        ],

    });
});

$("#addMemberSubmitBtn").click(function () {
    $(this).attr('disabled', true);
    $(this).val("รอสักครู่...");
    $("#addMemberForm").submit();
});

function clearInputModal() {
    let addMemberModal = $("#addMemberModal");
    addMemberModal.find("input").val("");
}

$("#randomCode").click(function () {
    $.post("/whs-center/members/set-members/randomCode", data = {
            _token: '{{ csrf_token() }}',
        },
        function (res) {
            $("#memberCode").val(res);
        }
    )
});

function showMemberType(id, name) {
    let memberType = $("#memberType");
    $.post("/whs-center/members/set-members/showMemberType", data = {
            _token: '{{ csrf_token() }}',
        },
        function (res) {
            memberType.empty();
            if (id == null && name == null) {
                res.forEach(element => {
                    memberType.append($("<option></option>").val(element.id).text(element.name));
                });
            } else {
                memberType.append($("<option></option>").val(id).text(name));
                res.forEach(element => {
                    if (element.id != id) {
                        memberType.append($("<option></option>").val(element.id).text(element.name));
                    }
                });
            }

        }
    );
}

function showBank(id, name) {
    let bankName = $("#bankName");
    $.post("/whs-center/members/set-members/showBank", data = {
            _token: '{{ csrf_token() }}',
        },
        function (res) {
            bankName.empty();
            bankName.append($("<option></option>").val('').text('กรุณาเลือก'));
            if (id == null && name == null) {
                res.forEach(element => {
                    bankName.append($("<option></option>").val(element.id).text(element.name));
                });
            } else {
                bankName.append($("<option></option>").val(id).text(name));
                res.forEach(element => {
                    if (element.id != id) {
                        bankName.append($("<option></option>").val(element.id).text(element.name));
                    }
                });
            }
        }
    );
}

function addMemberModal(id) {
    member_id = id;
    let memberType = $("#memberType");
    let bankName = $("#bankName");
    clearInputModal();
    $.ajax({
        type: "method",
        method: "POST",
        url: "/whs-center/members/set-members/checkMember",
        data: {
            _token: '{{ csrf_token() }}',
            member_id: id,
        },
        success: function (res) {
            $("#addMemberModal").modal('show');
            $("#memberCode").val(res.member.code);
            $("#idCard").val(res.customer.vat_code);
            if(res.bank){
                showBank(res.bank.id, res.bank.name);
            }else{
                showBank(null, null);
            }
            if(res.member_type){
                showMemberType(res.member_type.id, res.member_type.name);
            }else{
                showMemberType(null, null);
            }
            checkStatus(res.member.status);
        }
    });
}

function checkStatus(data) {
    let status = $("#memberStatus");
    if (data == 1) {
        status.text("รอดำเนินการ").css('color', 'gray');
    } else if (data == 2) {
        status.text("เป็นสมาชิกแล้ว").css("color", "green");
    } else {
        status.text("มีบางอย่างผิดพลาด");
    }
}

$("#saveMemberBtn").click(function () {
    if ($("#memberCode").val() != "" && $("#memberType").val() != "") {
        $.post("/whs-center/members/set-members/saveMember", data = {
                _token: '{{ csrf_token() }}',
                member_id: member_id,
                memberCode: $("#memberCode").val(),
                memberType: $("#memberType").val(),
                idCard: $("#idCard").val(),
                bankName: $("#bankName").val(),
                bankAccountNumber: $("#bankAccountNumber").val(),
            },
            function (res) {
                swal.fire(res.title, res.msg, res.status).then(function () {
                    if (res.status == 'success') {
                        member_table.ajax.reload(null, false);
                        $("#addMemberModal").modal('hide');
                    }
                });
            },
        );
    } else {
        swal.fire('เกิดข้อผิดพลาด', 'กรุณากรอกข้อมูลทุกช่อง', 'error');
    }
});

$(document).ready(function() {
        let merberType = $('#memberTypeAll');
        $.post("/whs-center/members/set-members/showMemberType", data = {
            _token: '{{ csrf_token() }}',
        },
        function (res) {
            console.log('ok');
            merberType.empty();
            merberType.append($("<option></option>").val('').text('กรุณาเลือก'));
            res.forEach(element => {
                merberType.append($("<option></option>").val(element.id).text(element.name));
            });

        }
    );

    let warehouse = $('#warehouseAll');
        $.post("/whs-center/members/set-members/showWarehouse", data = {
            _token: '{{ csrf_token() }}',
        },
        function (res) {
            console.log('ok');
            warehouse.empty();
            warehouse.append($("<option></option>").val('').text('กรุณาเลือก'));
            res.forEach(element => {
                warehouse.append($("<option></option>").val(element.id).text(element.name));
            });

        }
    );
});

$("#member_table").ready(function () {
    member_table = $('#member_table').DataTable({
        "processing": false,
        "serverSide": false,
        "info": false,
        "searching": true,
        "responsive": true,
        "bFilter": false,
        "bLengthChange": true,
        "destroy": true,
        "pageLength": 25,
        "order": [
            [6, "desc"]
        ],
        "ajax": {
            "url": "/whs-center/members/set-members/showMember",
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
                "targets": [5, 6, 7],
                "className": "text-center",
            },
        ],
        "columns": [{
                "data": "id",
            },
            {
                "data": "customer.code",
            },
            {
                "data": "code",
            },
            {
                "data": "member_type.name",
                "render": function (data) {
                    return (data == null) ? '' : data;
                }
            },
            {
                "data": "customer.name"
            },
            {
                "data": "customer.tel",
            },
            {
                "render": function (data, type, full) {
                    let created_at = moment(full.created_at).format('DD MMMM YYYY');
                    const pending =
                        `<span class="badge badge-success badge-pill">รอดำเนินการ</span>`;
                    const success =
                        `<span class="badge badge-primary badge-pill">${created_at}</span>`;

                    return (full.status == 1) ? pending : success;
                }
            },
            {
                "render": function (data, type, full) {
                    const haveCode =
                        `<button class="btn btn-primary btn-sm" onclick="addMemberModal(${full.id})"><i class="fa fa-plus"></i> เพิ่มเป็นสมาชิก</buuton>
                              <button class="btn btn-danger btn-sm" onclick="destroyMember(${full.id})"><i class="fa fa-minus"></i> ลบ</button>
                           `;
                    const noCode =
                        ` <a href="set-members/showProfile/${full.id}" class="btn btn-info btn-sm"><i class="fa fa-search-plus"></i> ดูรายละเอียด</a>
                         <button class="btn btn-danger btn-sm" onclick="destroyMember(${full.id})"><i class="fa fa-minus"></i> ลบสมาชิก</button>
                        `;
                    return (full.code) ? noCode : haveCode;
                }
            },
        ],
    });

});


function destroyMember(id) {
    Swal.fire({
        title: 'คุณมั่นใจหรือไม่ ?',
        text: "คุณค้องการลบสมาชิกนี้ใช่หรือไม่",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
    }).then((result) => {
        if (result.value) {
            $.post("/whs-center/members/set-members/destroyMember", data = {
                    _token: '{{ csrf_token() }}',
                    member_id: id,
                },
                function (res) {
                    (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                    member_table.ajax.reload(null, false);
                },
            );
        }
    });
    console.log('OK');
}

</script>

@stop
