@extends ('layouts-whs.app')

@section ('content')
    <div class="row">
        <form class="form-horizontal">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>
                        ใบเบิกทำป้ายแดง เลขที่ ( {{ $withdrawRedLabel->code }} )
                    </h2>
                    <button type="button" class="btn btn-success waves-effect button-approve">อนุมัติ</button>
                    <button type="button" class="btn btn-danger waves-effect button-non-approve">ไม่อนุมัติ</button>
                    <button class="btn btn-warning waves-effect" type="button" onclick="location.href='{{ route('whs.withdraw-red-label.approve.index') }}'">กลับ</button>
                </div>
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">คลัง :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $withdrawRedLabel->warehouse->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">เลขที่เอกสาร :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $withdrawRedLabel->code }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">วันที่เอกสาร :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{{ $withdrawRedLabel->document_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">หมายเหตุ :</label>
                        <div class="col-lg-9">
                            <p class="form-control-static">{!!  nl2br($withdrawRedLabel->detail) !!}</p>
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
                            <th width="48%" colspan="4" class="text-center">วัตถุดิบ</th>
                            <th width="4%" rowspan="2" class="text-center">แปลงเป็น</th>
                            <th width="48%" rowspan="2" class="text-center">ป้ายแดงที่เกิดขึ้น</th>
                        </tr>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th class="col-sm-1">จำนวน</th>
                            <th>หน่วยนับ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawRedLabel->withdrawRedLabelMaterials as $withdrawRedLabelMaterial)
                            <tr>
                                <td>{{ $withdrawRedLabelMaterial->coil_code != null ? $withdrawRedLabelMaterial->coil_code : $withdrawRedLabelMaterial->good->code }}</td>
                                <td>{{ $withdrawRedLabelMaterial->good->name }}</td>
                                <td>{{ number_format($withdrawRedLabelMaterial->amount, 2) }}</td>
                                <td>{{ $withdrawRedLabelMaterial->good->unit->name }}</td>
                                <td class="text-center"><i class="fa fa-arrow-right"></i></td>
                                <td>
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
                                        @foreach($withdrawRedLabelMaterial->withdrawRedLabelProducts as $withdrawRedLabelProduct)
                                            <tr>
                                                <td>{{ $withdrawRedLabelProduct->good->code }}</td>
                                                <td>{{ $withdrawRedLabelProduct->good->name }}</td>
                                                <td>{{ number_format($withdrawRedLabelProduct->amount, 2) }}</td>
                                                <td>{{ $withdrawRedLabelProduct->good->unit->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
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
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $withdrawRedLabel->createdUser->name !!}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->created_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                    @if ($withdrawRedLabel->edit_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้แก้ไข :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $withdrawRedLabel->editUser->name !!}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->edit_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($withdrawRedLabel->deleted_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้ลบ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $withdrawRedLabel->deletedUser->name !!}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->deleted_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($withdrawRedLabel->approve_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $withdrawRedLabel->approveUser->name !!}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->approve_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                    @endif
                    @if ($withdrawRedLabel->none_approve_user_id != 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ผู้ไม่อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $withdrawRedLabel->noneApproveUser->name !!}</label>
                            <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->none_approve_at->format('d/m/Y H:i:s') }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">สาเหตุที่ไม่อนุมัติ :</label>
                            <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $withdrawRedLabel->cancle_detail }}</label>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <form action="{{ route('whs.withdraw-red-label.approve.store', $withdrawRedLabel->id) }}" method="post" id="formStore">
        @csrf
        <input type="hidden" name="approveStatus" id="approveStatus">
        <input type="hidden" name="cancelDetail" id="cancelDetail">
    </form>

    <div class="modal inmodal fade" id="cancelModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>สาเหตุการไม่อนุมัติ</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="control-label col-lg-2"> รายละเอียด</label>
                                <div class="col-lg-9">
                                    <textarea id="cancelDetailModal" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary bt-non-approve">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $('.button-approve').on('click', function () {
            swal({
                title: "ยืนยันอนุมัติใบเบิกทำป้ายแดง?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#approveStatus').val(1);
                $('#formStore').submit();
            });
        });

        $('.button-non-approve').on('click', function () {
            $('#cancelModal').modal('show');
        });

        $('.bt-non-approve').on('click', function () {
            swal({
                title: "ยืนยันไม่อนุมัติใบเบิกทำป้ายแดง?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#approveStatus').val(2);
                $('#cancelDetail').val($('#cancelDetailModal').val());
                $('#formStore').submit();
            });
        });
    </script>
@stop
