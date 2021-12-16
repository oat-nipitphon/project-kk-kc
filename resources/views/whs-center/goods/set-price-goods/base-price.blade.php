@extends('layouts-whs-center.app')

@section('content')

    <form action="{{ route('whs-center.goods.set-price-goods.setBasePrice', $good->id) }}" method="POST" class="form-horizontal" id="setBasePriceForm">
        @csrf
        <div class="ibox">
            <div class="ibox-title">
                <h2>ราคากลางตามคลังสินค้า</h2>
            </div>
            <div class="ibox-content ibox-padding">
                <div class="form-group">
                    <label class="col-sm-3 control-label">รหัสสินค้า : </label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $good->code }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">ชื่อสินค้า : </label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $good->name }}</label>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-6">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ชื่อคลังสินค้า</th>
                                <th>แก้ไขล่าสุดเมื่อ</th>
                                <th>ราคากลาง</th>
                                <th>ราคาตามกลุ่มลูกค้า</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($goodWarehouses as $goodWarehouse)
                                <tr>
                                    <td>{{ $goodWarehouse->warehouse->name }}</td>
                                    <td>{{ $goodWarehouse->updated_at }}</td>
                                    <td>
                                        <input type="number" name="goodWarehouses[{{ $goodWarehouse->id }}]" value="{{ $goodWarehouse->base_price }}" class="form-control">
                                    </td>
                                    <td><button type="button" class="btn btn-success" onclick="infoPriceBtn({{ $good->id }},{{ $goodWarehouse->id }},{{ $goodWarehouse->warehouse->id }},{{ $goodWarehouse->base_price }})">ดูรายละเอียด</button></td>
                         
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="ibox-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="setBasePriceSubmitBtn">
                        บันทึก
                    </button>
                </div>
            </div>
        </div>
    </form>

<!--Info Modal -->
<div class="modal fade" id="infoPriceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">ตั้งค่าราคาสินค้า </h2>
            </div>
            <div class="modal-body" style="padding:20px;">
                <input type="hidden" id="good_warehouse_id" name="good_warehouse_id" value="">
             
                    <div class="form-group">
                        <label class="col-sm-3 control-label">รหัสสินค้า :</label>
                        <label class="col-sm-9" style="padding-top:7px;"><span id="code"></span></label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ชื่อสินค้า :</label>
                        <label class="col-sm-9" style="padding-top:7px;"><span id="name"></span></label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">สาขา :</label>
                        <label class="col-sm-9" style="padding-top:7px;"><span id="warehouseCode"></span> - <span id="warehouseName"></span></label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ราคากลาง :</label>
                        <label class="col-sm-9" style="padding-top:7px;"><span id="basePrice"></span> บาท</label>
                    </div>

                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <table class="table table-bordered" id="member_type_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ประเภทกลุ่มสมาชิก</th>
                                                <th>ราคา [บาท]</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mem_type">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="ibox-footer">
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" id="setPriceBtn">
                                    บันทึก
                                </button><span> </span>
                                <button type="button" class="btn btn-default" id="closeModal">
                                    ยกเลิก
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
<script>

    $("#setBasePriceSubmitBtn").click(function () {
        $(this).attr('disabled', true);
        $(this).val("รอสักครู่...")
        $("#setBasePriceForm").submit();
    });

    $("#closeModal").click(function () {
        $("#infoPriceModal").modal('hide');
    });

    function infoPriceBtn(good_id, good_warehouse_id, warehouse_id, base_price) {

        $.post("/whs-center/goods/set-price-goods/${good_id}/infoGood", data = {
                _token: '{{ csrf_token() }}',
                good_id: good_id,
                good_warehouse_id: good_warehouse_id,
                warehouse_id: warehouse_id,
            },
            function (res) {
                $('#infoPriceModal').modal("show");
                $("#code").text(res[0].good.code);
                $("#name").text(res[0].good.name);
                $("#warehouseCode").text(res[0].warehouse.code);
                $("#warehouseName").text(res[0].warehouse.name);
                $("#basePrice").text(base_price);
                $("#good_warehouse_id").val(good_warehouse_id);
                $("#mem_type").empty();
                res.forEach(element => {
                    $("#mem_type").append(`
                            <tr>
                                <td>${element.member_type_name}</td>
                                <td><input type="number" member_type_id="${element.member_type_id}" name="mem_type_id[${element.member_type_id}]" value="${element.good_price}" class="setPriceForm form-control"></td>
                            </tr>
                            `);
                });

            },
        );
    }

    $("#setPriceBtn").click(function () {

        var good_prices = [];
        var setPriceForm = $(".setPriceForm");
        setPriceForm.each(function () {
            good_price = {
                'member_type_id': $(this).attr('member_type_id'),
                'price': $(this).val(),
            };
            good_prices.push(good_price);
        });

        $.post("/whs-center/goods/set-price-goods/${good_id}/setPrice", data = {
                _token: '{{ csrf_token() }}',
                good_warehouse_id: $("#good_warehouse_id").val(),
                good_prices: good_prices,
            },
            function (res) {
                swal.fire(res.title, res.msg, res.status).then(function () {
                    if (res.status == 'success') {
                        $("#infoPriceModal").modal('hide');
                    }
                });
            },
        );
    });

</script>              


@stop
