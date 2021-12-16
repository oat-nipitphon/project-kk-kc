<div class="ibox-content">
    <div class="row">
        <div class="col-lg-3">
            <label>คลังเก็บสินค้า</label>
            <div class="fg-line">
                <input type="hidden" value="{{ session('warehouse')['id'] }}" name="warehouse_id">
                <input type="text" value="{{ session('warehouse')['name'] }}" class="form-control" readonly>
            </div>
        </div>
        <div class="col-lg-3">
            <label>เลขที่ใบเอกสาร</label>
            <div class="fg-line">
                @if(isset($requisition))
                    <input type="text" value="{{ $requisition->code }}" class="form-control" readonly>
                @else
                    <input type="text" value="{{ session('warehouse')['code'] }}RQxxxx-xxx" class="form-control" readonly>
                @endif
            </div>
        </div>
        <div class="col-lg-3">
            <label>วันที่เอกสาร</label>
            <div class="input-group">
                <div class="fg-line">
                    <input type="text" name="document_at" value="{{ isset($requisition) ? $requisition->document_at->format('d/m/Y') : \Carbon\Carbon::today()->format('d/m/Y') }}" class="form-control" data-type="date" required>
                </div>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
        <div class="col-lg-3">
            <label>เลือกประเภทการเบิก</label>
            <div class="fg-line">
                <select name="take_id" class="form-control">
                    @foreach($takes as $take)
                        <option value="{{ $take->id }}" {{ isset($requisition) && $requisition->take_id == $take->id ? 'selected' : '' }}>{{ $take->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div><br>
<div class="tabs-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">รายการสินค้า</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="detail">
            <div class="panel-body">
                <span class="pull-right">
                    <a type="button" class="btn btn-primary" id="getGoodModal">เพิ่มสินค้า</a>
                </span>
                <h3>รายการสินค้า</h3>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th class="col-sm-1">จำนวน</th>
                        <th>หน่วยนับ</th>
                        <th>จำนวนคงเหลือในคลัง</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="goodAppend">
                    @if(isset($requisition))
                        @foreach($requisition->requisitionGoods as $key => $requisitionGood)
                            <tr class="trNumber" data="{{ $key }}">
                                <input type="hidden" name="materialGoodId[{{ $key }}]" class="goodId" value="{{ $requisitionGood->good_id }}">
                                <input type="hidden" name="materialGoodCoilCode[{{ $key }}]" class="goodCoilCode" value="{{ $requisitionGood->coil_code }}">
                                <td class="goodCode">{{ $requisitionGood->coil_code != null ? $requisitionGood->coil_code : $requisitionGood->good->code }}</td>
                                <td class="goodName">{{ $requisitionGood->good->name }}</td>
                                <td class="goodMaterialAmount"><input type="number" name="materialAmount[{{ $key }}]" value="{{ $requisitionGood->amount }}" min="0.1" class="form-control"></td>
                                <td class="goodUnitName">{{ $requisitionGood->good->unit->name }}</td>
                                <td class="goodBalanceAmount">{{ $requisitionGood->good_balance_amount }}</td>
                                <td class="text-center"><button class="btn btn-danger dim deleteRow" type="button"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><br>
<div class="ibox">
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <label>หมายเหตุ</label>
                <textarea name="detail" cols="50" rows="10" class="form-control">
                    @if (isset($requisition))
                        {{ $requisition->detail }}
                    @endif
                </textarea>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="goodModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">ค้นหาสินค้า</h4>
            </div>
            <div class="modal-body" style="padding:20px;" id="dataGoods">

            </div>
        </div>
    </div>
</div>
