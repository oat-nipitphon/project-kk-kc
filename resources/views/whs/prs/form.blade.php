<div class="ibox-content">
    <div class="row">
        <div class="col-lg-3">
            <label>คลังเก็บสินค้า</label>
            <div class="fg-line">
                <input type="text" value="{{ session('warehouse')['name'] }}" class="form-control" readonly>
            </div>
        </div>
        <div class="col-lg-3">
            <label>เลขที่ใบเอกสาร</label>
            <div class="fg-line">
                <input type="text" value="PRxxxx-xxx" class="form-control" readonly>
            </div>
        </div>
        <div class="col-lg-3">
            <label>วันที่เอกสาร</label>
            <div class="input-group">
                <div class="fg-line">
                    <input type="text" name="document_at" value="{{ isset($pr) ? $pr->document_at->format('d/m/Y') : \Carbon\Carbon::today()->format('d/m/Y') }}" class="form-control" data-type="date" required>
                </div>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
        <div class="col-lg-3">
            <label>ต้องการภายในวัน</label>
            <div class="input-group">
                <div class="fg-line">
                    <input type="text" name="required_at" value="{{ isset($pr) ? $pr->required_at->format('d/m/Y') : \Carbon\Carbon::today()->format('d/m/Y') }}" class="form-control" data-type="date" required>
                </div>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
                    <a type="button" class="btn btn-primary" onclick="getGoodModal()">เพิ่มสินค้า</a>
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
                    @if(isset($pr))
                        @foreach($pr->prGoods as $prGood)
                            <tr>
                                <td>
                                    <span class="good_code">{{ $prGood->good->code }}</span>
                                    <input type="hidden" name="good_id[]" value="{{ $prGood->good_id }}" class="good_id">
                                </td>
                                <td>
                                    <span class="good_name">{{ $prGood->good->name }}</span><br><br>
                                    <textarea name="goodDetail[]" cols="50" rows="10" class="form-control">{{ $prGood->detail }}</textarea>
                                </td>
                                <td>
                                    <label>
                                        <input type="text" name="amount[]" class="form-control amount" data-type="number" value="{{ $prGood->amount }}">
                                    </label>
                                </td>
                                <td>
                                    <span class="good_unit">
                                        {{ $prGood->unit->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="amount_in_warehouse">
                                        @if ($prGood->good->goodView != null)
                                            {{ number_format($prGood->good->goodView->balance_amount, 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" name="button" class="btn btn-danger deleteRow">ลบ</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if(isset($select_goods))
                        @foreach($select_goods as $select_good)
                            <tr>
                                <td>
                                    <span class="good_code">{{ $select_good->code }}</span>
                                    <input type="hidden" name="good_id[]" value="{{ $select_good->id }}" class="good_id">
                                </td>
                                <td>
                                    <span class="good_name">{{ $select_good->name }}</span><br><br>
                                    <textarea name="goodDetail[]" cols="50" rows="10" class="form-control"></textarea>
                                </td>
                                <td>
                                    <label>
                                        <input type="text" name="amount[]" class="form-control amount" data-type="number">
                                    </label>
                                </td>
                                <td>
                                    <span class="good_unit">
                                        {{ $select_good->unit->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="amount_in_warehouse">
                                        @if ($select_good->goodView != null)
                                            {{ number_format($select_good->goodView->balance_amount, 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" name="button" class="btn btn-danger deleteRow">ลบ</button>
                                </td>
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
                <textarea name="detail" cols="50" rows="10" class="form-control"></textarea>
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
                @include('whs.prs.table')
            </div>
        </div>
    </div>
</div>
