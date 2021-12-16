@extends ('layouts-whs.app')

@section ('content')
    <div class="row">
        <form class="form-horizontal">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>
                        ใบเบิกสินค้า เลขที่ ( {{ $requisition->code }} )
                    </h2>
                    @if($requisition->approve_user_id == 0)
                        <a href="{{ route('whs.requisitions.edit', $requisition->id) }}" class="btn btn-info waves-effect">แก้ไขใบเบิกสินค้า</a>
                    @endif
                    <button type="button" class="btn btn-danger waves-effect button-delete">ยกเลิกใบเบิกสินค้า</button>
                    <button class="btn btn-warning waves-effect" type="button" onclick="location.href='{{ route('whs.requisitions.index') }}'">กลับ</button>
                </div>
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">คลัง :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $requisition->warehouse->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">เลขที่เอกสาร :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $requisition->code }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">วันที่เอกสาร :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $requisition->document_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ประเภทการเบิก :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $requisition->take->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">หมายเหตุ :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{!!  nl2br($requisition->detail) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="ibox">
            <div class="ibox-content">
                <h2>รายการสินค้า</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th class="col-sm-1">จำนวน</th>
                            <th>หน่วยนับ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requisition->requisitionGoods as $requisitionGood)
                            <tr>
                                <td>{{ $requisitionGood->coil_code != null ? $requisitionGood->coil_code : $requisitionGood->good->code }}</td>
                                <td>{{ $requisitionGood->good->name }}</td>
                                <td>{{ number_format($requisitionGood->amount, 2) }}</td>
                                <td>{{ $requisitionGood->good->unit->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <form class="form-horizontal">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>
                        Log บันทึกแก้ไข
                    </h2>
                </div>
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ผู้บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->createUser->name }}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->created_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                    @if ($requisition->edit_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้แก้ไข :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->editUser->name }}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->edit_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($requisition->deleted_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้ลบ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->deletedUser->name }}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->deleted_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($requisition->approve_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->approveUser->name }}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->approve_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($requisition->none_approve_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้ไม่อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->noneApproveUser->name }}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->none_approve_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">สาเหตุที่ไม่อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $requisition->cancle_detail }}</label>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <form action="{{ route('whs.requisitions.destroy', $requisition->id) }}" method="post" id="formDestroy">
        @csrf
        @method('delete')
    </form>
@stop

@section('script')
    <script type="text/javascript">
        $('.button-delete').click(function () {
            swal({
                title: "ยืนยันยกเลิกใบเบิกสินค้า?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#formDestroy').submit();
            });
        });
    </script>
@stop
