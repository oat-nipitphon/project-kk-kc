@extends('layouts-whs-center.app')

@section('css')

@stop

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>ข้อมูลสมาชิก</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('whs-center/dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('whs-center/members/set-members') }}"> ตั้งค่าสมาชิก </a>
            </li>
            <li class="breadcrumb-item active">
                <strong>ข้อมูลสมาชิก</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            <button class="btn btn-warning" onclick="addMemberModal({{ $member->id }})"><i class="fa fa-pencil"></i> แก้ไข</button>
            <a href="{{ url()->previous() }}" class="btn btn-white"> ย้อนกลับ </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="row">
        {{-- <div class="col-md-4"> --}}
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Profile</h5>
                </div>

                <div>
                    <div class="ibox-content no-padding border-left-right">
                        <img src="{{ asset('storage/image/member/'.$member->avatar) }}" id="avatar" class="img-thumbnail" style="border-radius:50%; margin-right:25px; width:100%; max-width:300px;" class="img-fluid" alt="avatar">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <form action="{{ route('whs-center.members.set-members.uploadAvatar') }}" enctype="multipart/form-data" method="POST" id="uploadAvatarForm">
                                @csrf
                                <input type="hidden" value="{{$member->id}}" name="member_id">
                                <input type="file" class="form-control" id="inpufile" name="inpufile" required="true" />
                            </form>
                            <div class="btn-info input-group-addon" style="cursor:pointer" id="uploadAvatarBtn"> Upload </div>
                        </div>
                    </div>
                    <div class="ibox-content profile-content">
                        <div class="panel-heading"><h2><strong>{{ $member->customer->name }}</strong></h2></div>
                        <br>
                        <div class="panel-body"><b>รหัสลูกค้า : </b>{{ $member->customer->code }}</div>
                        <div class="panel-body"><b>ชื่อ : </b>{{ $member->customer->name }}  </div>
                        <div class="panel-body"><b>รหัสสมาชิก : </b>{{ $member->code }}  </div>
                        <div class="panel-body"><b>ประเภทสมาชิก :  </b> {{ $member->member_type->name }} </div>
                        <div class="panel-body"><b>เบอร์โทรศัพท์ : </b>{{ $member->customer->tel }}  </div>
                        <div class="panel-body"><b>เริ่มเป็นสมาชิกเมื่อ : </b>{{ $member->created_at }}  </div>
                        <div class="panel-body"><b>เลขบัตรประชาชน : </b>{{ $member->customer->vat_code }}  </div>
                        @if (  $member->bank )
                            <div class="panel-body"><b>ธนาคาร : </b>{{ $member->bank->name }}  </div>
                        @else
                            <div class="panel-body"><b>ธนาคาร : </b></div>
                        @endif
                        <div class="panel-body"><b>เลขบัญชี : </b>{{ $member->bank_account_number }}  </div>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
        {{-- <div class="col-md-6"> --}}
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Balance</h5>
                </div>
                <div class="ibox-content">
                    <span href="#" class="float-left">
                        <img alt="image"  style="width:100%;max-width:50px" class="rounded-circle" src="https://image.flaticon.com/icons/svg/639/639365.svg">
                    </span>
                    <h2>ยอดบิลรวม : <strong>{{$totalAmount}}</strong> บาท</h2>
                </div>
                <div class="ibox-content">
                    <h2>แต้มสะสม : <strong>{{$totalPoint}}</strong> แต้ม<h2> <a href="{{$member->id}}/showPointDetail"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> ดูรายละเอียด </a>
                </div>
                <div class="ibox-content">
                    <h2>ผลประโยชน์สะสม : <strong>{{$totalBenefit}}</strong> บาท</h2> <a href="{{$member->id}}/showBenefitDetail"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> ดูรายละเอียด </a>
                </div>
            </div>

        {{-- </div> --}}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">แก้ไขข้อมูลสมาชิก</h4>
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

 $("#addMemberSubmitBtn").click(function () {
    $(this).attr('disabled', true);
    $(this).val("รอสักครู่...");
    $("#addMemberForm").submit();
});

$("#uploadAvatarBtn").click(function () {
    var imgVal = $('[type=file]').val();
        if (imgVal == '') {
            alert("กรุณาเลิอกรูปภาพ!!!");
            return false;
        }
    $("#uploadAvatarForm").submit();
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
})

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
            if (id == null && name == null) {
                bankName.append($("<option></option>").val('').text('กรุณาเลือก'));
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
            $("#bankAccountNumber").val(res.member.bank_account_number);
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
        $.ajax({
            type: "method",
            method: "POST",
            url: "/whs-center/members/set-members/saveMember",
            data: {
                _token: '{{ csrf_token() }}',
                member_id: member_id,
                memberCode: $("#memberCode").val(),
                memberType: $("#memberType").val(),
                idCard: $("#idCard").val(),
                bankName: $("#bankName").val(),
                bankAccountNumber: $("#bankAccountNumber").val(),
            },
        success: function (res) {
            swal.fire(res.title, res.msg, res.status).then(function () {
                if (res.status == 'success') {
                    $("#addMemberModal").modal('hide');
                    location.reload();
                }
            });
        }
    });

    } else {
        swal.fire('เกิดข้อผิดพลาด', 'กรุณากรอกข้อมูลทุกช่อง', 'error');
    }
});

</script>

@stop
