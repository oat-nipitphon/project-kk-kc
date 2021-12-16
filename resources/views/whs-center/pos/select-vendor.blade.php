@extends('layouts-whs-center.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="ibox">
        <div class="form-horizontal">
            <div class="ibox-title">
                <h2>รายละเอียดใบ Pr </h2>
                <button type="button" class="btn btn-danger" onclick="showModal()">ยกเลิกใบ Pr นี้</button>
                <button type="button" class="btn btn-warning waves-effect" id="clearPrButton">เคลียร์ใบ Pr นี้ (กรณีสั่งไม่หมด)</button>
            </div>
            <div class="ibox-content ibox-padding">
                <div class="form-group">
                    <input type="hidden" name="pr_id" value="{{ $pr->id }}">
                    <label class="col-sm-3 control-label">รหัสใบ Pr :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $pr->code }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">วันที่เอกสาร :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $pr->document_at->format('d/m/Y') }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">ต้องการภายในวันที่ :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $pr->required_at->format('d/m/Y') }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">คลังสินค้า :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $pr->warehouse->name }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">หมายเหตุ :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{!! nl2br($pr->detail) !!}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">ข้อมูลสินค้าที่ต้องการ :</label>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>หน่วยนับ</th>
                            <th>จำนวนคงเหลือในคลัง</th>
                            <th>ราคาล่าสุดที่เคยซื้อ</th>
                            <th>ซื้อจาก Supplier</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pr->prGoods as $prGood)
                            <tr>
                                <td class="font-bold">{{ $prGood->good->code }}</td>
                                <td>
                                    <span class="font-bold">{{ $prGood->good->name }}</span>
                                    @if ($prGood->detail != null)
                                        <br>{!! nl2br($prGood->detail) !!}
                                    @endif
                                </td>
                                <td>{{ number_format($prGood->amount, 2) }}</td>
                                <td>{{ $prGood->unit->name }}</td>
                                <td>{{ $prGood->amount_in_warehouse }} {{ $prGood->unit->name }}</td>
                                <td style="background-color:#00FF00">{{ $prGood->last_price }}</td>
                                <td>{{ $prGood->last_vendor }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <a href="{{ route('whs-center.vendors.create') }}" class="btn btn-info">
                เพิ่มคู่ค้า
            </a>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table id="dataGrid" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>รหัสคู่ค้า</th>
                        <th>ชื่อ</th>
                        <th>เลขที่เสียภาษี</th>
                        <th>เลือก</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->vendor_code }}</td>
                            <td>{{ $vendor->vendor_th_name }}</td>
                            <td>{{ $vendor->vendor_tax_number }}</td>
                            <td>
                                <a href="{{ route('whs-center.pos.create', ['pr_id' => $pr->id, 'vendor_id' => $vendor->id]) }}"
                                   class="btn btn-primary btn-xs">
                                    <i class="fa fa-hand-o-up" aria-hidden="true"></i>&nbsp;เลือก
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="{{ route('whs-center.pos.cancel-pr', $pr->id) }}" method="POST" class="form-horizontal" id="cancelPrForm">
            @csrf
            @method('delete')
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        <h4 class="modal-title">กรุณากรอกสาเหตุที่ไม่อนุมัติใบสั่งซื้อนี้</h4>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="cancel_detail" rows="10"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form action="{{ route('whs-center.pos.clear-pr', $pr->id) }}" method="POST" class="form-horizontal" id="clearPrForm">
        @csrf
        @method('delete')
    </form>
@stop

@section('script')
    <script src="/js/plugins/dataTables/datatables.min.js"></script>

    <script type="text/javascript">
        $(function(){
            $('#dataGrid').dataTable();
        });

        function showModal() {
            $('#myModal5').modal('show');
        }

        $('#clearPrButton').on('click', function () {
            swal({
                title: "Are you sure?",
                text: "ยืนยันเคลียใบขอซื้อนี้!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน!",
                closeOnConfirm: false
            }, function () {
                $('#clearPrForm').submit();
            });
        });
    </script>
@stop
