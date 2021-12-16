@extends ('layouts-whs.app')

@section ('content')
    <form class="form-horizontal">
        <div class="ibox">
            <div class="ibox-title">
                <h2>
                    ใบขอซื้อสินค้า เลขที่ ( {{ $pr->code }} )
                    @if($pr->edit_user_id > 0)
                        <span style="font-style: italic;"> (สำเนาการแก้ไข)</span>
                    @endif
                    @if($pr->deleted_user_id > 0)
                        <span style="font-style: italic;color:red;"> (ใบสั่งซื้อถูกยกเลิกไปแล้ว)</span>
                    @endif
                </h2>
                @if(Route::is('whs.prs.show'))
                    <a href="{{ route('whs.prs.edit', $pr->id) }}" class="btn btn-info waves-effect" style="font-size: 14px;">แก้ไขใบขอซื้อสินค้า</a>
                    <button type="button" class="btn btn-danger waves-effect {{ $pr->user_approve_id != 0 ? 'hidden' : '' }} button-delete">ยกเลิกใบขอซื้อสินค้า</button>
                    <button class="btn btn-warning waves-effect" type="button" onclick="location.href='{{ route('whs.prs.index') }}'">ยกเลิก</button>
                @endif
                @if(Route::is('whs.prs-approve.show'))
                    <button type="button" class="btn btn-primary approveButton">
                        อนุมัติใบขอซื้อสินค้า
                    </button>
                    <button type="button" class="btn btn-danger nonApproveButton">
                        ไม่อนุมัติ
                    </button>
                    <button class="btn btn-warning waves-effect" type="button" onclick="location.href='{{ route('whs.prs-approve.index') }}'">ยกเลิก</button>
                @endif
            </div>
            <div class="ibox-content">
                <div class="form-group">
                    <label class="col-sm-3 control-label">เลขที่เอกสาร :</label>
                    <label class="col-sm-3 control-label" style="padding-top:7px;">{{ $pr->code }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">วันที่เอกสาร :</label>
                    <label class="col-sm-3 control-label" style="padding-top:7px;">{{ $pr->document_at->format('d/m/Y') }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">วันที่ต้องการสินค้า :</label>
                    <label class="col-sm-3 control-label" style="padding-top:7px;">{{ $pr->required_at->format('d/m/Y') }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">คลังที่ขอซื้อ :</label>
                    <label class="col-sm-3 control-label" style="padding-top:7px;">{{ $pr->warehouse->name }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">หมายเหตุ :</label>
                    <label class="col-sm-9 control-label" style="padding-top:7px;">{!!  nl2br($pr->detail) !!}</label>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-content">
                <h2>รายการสินค้า</h2>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataGrid">
                        <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>หน่วยนับ</th>
                            <th>จำนวนคงเหลือในคลัง</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!Route::is('prs.report.detail'))
                            @foreach($pr->prGoods as $key => $prGood)
                                <tr>
                                    <td class="font-bold">{{ $prGood->good->code }}</td>
                                    <td>
                                        <span class="font-bold">{{ $prGood->good->name }}</span>
                                        @if ($prGood->detail != null)
                                            <br>{!!  nl2br($prGood->detail) !!}
                                        @endif
                                    </td>
                                    <td>{{ number_format($prGood->amount, 2) }}</td>
                                    <td>{{ $prGood->unit->name }}</td>
                                    <td>
                                        @if ($prGood->good->goodView != null)
                                            {{ number_format($prGood->good->goodView->balance_amount, 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($pr->prGoods()->withTrashed()->get() as $key => $prGood)
                                <tr>
                                    <td class="font-bold">{{ $prGood->good->code }}</td>
                                    <td>
                                        <span class="font-bold">{{ $prGood->good->name }}</span>
                                        @if ($prGood->detail != null)
                                            <br>{!!  nl2br($prGood->detail) !!}
                                        @endif
                                    </td>
                                    <td>{{ number_format($prGood->amount, 2) }}</td>
                                    <td>{{ $prGood->unit->name }}</td>
                                    @if ($prGood->good->goodView != null)
                                        {{ number_format($prGood->good->goodView->balance_amount, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h2>
                    Log บันทึกแก้ไข
                </h2>
            </div>
            <div class="ibox-content">
                <div class="form-group">
                    <label class="col-sm-2 control-label">ผู้บันทึก :</label>
                    <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $pr->createdUser->name !!}</label>
                    <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                    <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->created_at->format('d/m/Y H:i:s') }}</label>
                </div>
                @if ($pr->edit_user_id != 0)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ผู้แก้ไข :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $pr->editUser->name !!}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->edit_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                @endif
                @if ($pr->deleted_user_id != 0)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ผู้ลบ :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $pr->deletedUser->name !!}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->deleted_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                @endif
                @if ($pr->approve_user_id != 0)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ผู้อนุมัติ :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $pr->approveUser->name !!}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->approve_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                @endif
                @if ($pr->none_approve_user_id != 0)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ผู้ไม่อนุมัติ :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{!!  $pr->noneApproveUser->name !!}</label>
                        <label class="col-sm-2 control-label">วันที่บันทึก :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->none_approve_at->format('d/m/Y H:i:s') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">สาเหตุที่ไม่อนุมัติ :</label>
                        <label class="col-sm-2 control-label" style="padding-top:7px;">{{  $pr->cancle_detail }}</label>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <form action="{{ route('whs.prs.destroy', $pr->id) }}" method="post" id="formDestroy">
        @csrf
        @method('delete')
        <input type="hidden" name="updated_at" value="{{ $pr->updated_at }}">
    </form>

    <div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="{{ route('whs.prs-approve.update', $pr->id) }}" method="post" id="formField" class="form-horizontal">
            @csrf
            @method('PUT')
            <input type="hidden" name="updated_at" value="{{ $pr->updated_at }}">
            <input type="hidden" name="is_approve" id="isApprove">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        <h4 class="modal-title">กรุณากรอกสาเหตุที่ไม่อนุมัติใบสั่งซื้อนี้</h4>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="cancle_detail" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" id="buttonSaveModal" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $('.button-delete').click(function () {
            swal({
                title: "ยืนยันการลบใบสั่งซื้อ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#formDestroy').submit();
            });
        });

        $('.approveButton').click(function () {
            swal({
                title: "ยืนยันอนุมัติใบขอซื้อ?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#isApprove').val(1);
                $('#formField').submit();
            });
        });

        $('.nonApproveButton').click(function () {
            $('#myModal5').modal('show');
        });

        $('#buttonSaveModal').click(function () {
            $(this).attr("disabled", true);
            $('#isApprove').val(0);
            $('#formField').submit();
        });
    </script>
@stop
