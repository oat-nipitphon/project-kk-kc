@extends('layouts-whs.app')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h3>รายงานใบขอซื้อสินค้า</h3>
        </div>
        <div class="ibox-content">
            <form method="GET" action="{{ route('whs.prs-report.index') }}" class="form-horizontal">
                <div class="row">
                    <div class="col-lg-3">
                        <label>คลังที่เก็บสินค้า</label>
                        <input type="text" value="{{ session('warehouse')['name'] }}" class="form-control" readonly>
                    </div>
                    <div class="col-lg-3">
                        <label>ตั้งแต่วันที่</label>
                        <div class="input-group">
                            <div class="fg-line">
                                <input type="text" name="start_at" value="{{ $start_at }}" class="form-control" data-type="date">
                            </div>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>จนถึงวันที่</label>
                        <div class="input-group">
                            <div class="fg-line">
                                <input type="text" name="end_at" value="{{ $end_at }}" class="form-control" data-type="date">
                            </div>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();" class="btn btn-primary" style="margin-top:23px;">ค้นหา</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered" style="margin-top:20px;">
                <thead>
                <tr>
                    <th>รหัสเอกสาร</th>
                    <th>วันที่ เวลา</th>
                    <th>ผู้สร้าง</th>
                    <th>ผู้แก้ไข</th>
                    <th>ผู้ลบ</th>
                    <th>ผู้อนุมัติ</th>
                    <th>ผู้ไม่อนุมัติ</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($prs != '')
                    @foreach($prs as $key => $pr)
                        <tr>
                            <td>
                                @if($pr->deleted_user_id == 0)
                                    {{ $pr->code }}
                                @else
                                    {{ $pr->code }}<span style="font-style: italic;color:red;"> (เอกสารยกเลิก)</span>
                                @endif
                                @if(0 < $pr->prGoods->where('cancle_status', 1)->count() && $pr->prGoods->where('cancle_status', 1)->count()< $pr->prGoods->count())
                                    <span class="text-warning" style="font-style: italic;"> (เอกสารยกเลิกบางส่วน)</span>
                                @endif
                            </td>
                            <td>{{ $pr->document_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if($pr->created_user_id != 0)
                                    <span class="label label-primary">{{ $pr->createdUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                @if($pr->edit_user_id != 0)
                                    <span class="label label-warning">{{ $pr->editUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                @if($pr->deleted_user_id != 0)
                                    <span class="label label-plain">{{ $pr->deletedUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                @if($pr->approve_user_id != 0)
                                    <span class="label label-success">{{ $pr->approveUser->username }}</span>
                                @endif
                            </td>
                            <td>
                                @if($pr->none_approve_user_id != 0)
                                    <span class="label label-danger">{{ $pr->noneApproveUser->username }}</span>
                                @endif
                            </td>
                            <td><a href="{{ route('whs.prs-report.show', $pr->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</td>
                        </tr>
                        @if($pr->parentPr != null)
                            @include('whs.prs.closure', ['pr' => $pr->parentPr])
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" style="text-align: center;">กรุณาเลือกคลังและวันที่ที่ต้องการดูข้อมูล</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@stop
