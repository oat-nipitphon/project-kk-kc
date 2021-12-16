<div class="table-responsive">
    <table class="table table-bordered dataTables-example" id="tableGood">
        <thead>
        <tr>
            <th>
                ประเภทสินค้า
            </th>
            <th>
                รหัสสินค้า
            </th>
            <th>
                ชื่อสินค้า
            </th>
            <th>
                จำนวนคงเหลือ
            </th>
            <th>
                หน่วยนับ
            </th>
            <th>
                <input type="checkbox" id="check-sku-all">
            </th>
        </tr>
        </thead>
        <tbody id="bodyTableGood">
        @foreach($goods as $good)
            <tr>
                <input type="hidden" class="goodId" value="{{ $good->id }}">
                <input type="hidden" class="typeId" value="{{ $good->type_id }}">
                <td class="goodTypeName">
                    {{ $good->type->name }}
                </td>
                <td class="goodCode">
                    @if ($good->type_id == 1)
                        -
                    @else
                        {{ $good->code }}
                    @endif
                </td>
                <td class="goodName">
                    {{ $good->name }}
                </td>
                <td class="goodAmount">
                    @if ($good->goodView != null)
                        {{ $good->goodView->balance_amount }}
                    @else
                        0.00
                    @endif
                </td>
                <td class="goodUnitName">
                    {{ $good->unit->name }}
                </td>
                <td>
                    <input type="checkbox" class="product-class">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        <button class="btn btn-primary " type="button" id="selectProduct"><i class="fa fa-check"></i>&nbsp;เลือก</button>
    </div>
</div>
