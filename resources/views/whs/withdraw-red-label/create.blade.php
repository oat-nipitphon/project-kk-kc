@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('whs.withdraw-red-label.store') }}" class="form-horizontal" id="FormSubmit">
            @csrf
            <div class="ibox">
                <div class="ibox-title">
                    <h3>สร้างใบเบิกสินค้าทำป้ายแดง</h3>
                    <button type="button" class="btn btn-primary" id="submitButton">บันทึก</button>
                    <a href="{{ route('whs.withdraw-red-label.index') }}" class="btn btn-warning waves-effect">ยกเลิก</a>
                </div>
                @include('whs.withdraw-red-label.form')
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="/js/plugins/dataTables/datatables.min.js"></script>
    <script type="text/javascript">
        let currentRow;
        $('#getGoodModal').on('click', function () {
            $('#dataGoods').empty();
            $.ajax({
                url: '/stock-table',
                type: 'get',
                data: {
                  'only_array' : null,
                  'except_array' : [12],
                },
                success: function (response) {
                    $('#dataGoods').html(response.html);
                    $("#stockTable").dataTable();
                    $('#goodModal').modal('show');
                },
                error: function () {
                    swal("ติดต่อฐานข้อมูลไม่ได้ โปรดแจ้งเจ้าหน้าที่");
                }
            });
        });

        $(document).on("click", ".btnGetStock", function () {
            const stockRow = $(this).closest("tr");
            const stock_good_id = stockRow.find('.stock_good_id').val();
            const stock_coil_code = stockRow.find('.stock_coil_code').val();
            const stock_good_code = stockRow.find('.stock_good_code').val();
            const stock_good_name = stockRow.find('.stock_good_name').val();
            const stock_balance_amount = stockRow.find('.stock_balance_amount').val();
            const stock_unit_name = stockRow.find('.stock_unit_name').val();

            const numberRow = Math.floor((Math.random() * 1000) + 1);

            let mainRow = '<tr class="trNumber" data="'+numberRow+'">';
            mainRow += '<input type="hidden" name="materialGoodId['+numberRow+']" class="goodId" value="'+stock_good_id+'">';
            if (stock_coil_code != "") {
                mainRow += '<input type="hidden" name="materialGoodCoilCode['+numberRow+']" class="goodCoilCode" value="'+stock_coil_code+'">';
                mainRow += '<td class="goodCode">'+stock_coil_code+'</td>';
            } else {
                mainRow += '<input type="hidden" name="materialGoodCoilCode['+numberRow+']" class="goodCoilCode">';
                mainRow += '<td class="goodCode">'+stock_good_code+'</td>';
            }
            mainRow += '<td class="goodName">'+stock_good_name+'</td>';
            mainRow += '<td class="goodMaterialAmount"><input type="number" name="materialAmount['+numberRow+']" value="0" min="0.1" class="form-control"></td>';
            mainRow += '<td class="goodUnitName">'+stock_unit_name+'</td>';
            mainRow += '<td class="goodBalanceAmount">'+stock_balance_amount+'</td>';
            mainRow += '<td class="text-center"><button class="btn btn-warning dim getRedLabelModal" type="button"><i class="fa fa-arrow-right"></i></button></td>';
            mainRow += '<td class="text-center"><table class="table table-bordered redLabelProductTable"><thead><tr><th>รหัสสินค้า</th><th>ชื่อสินค้า</th><th>จำนวน</th><th>หน่วยนับ</th><th></th></tr></thead><tbody class="redLabelAppend"></tbody></table></td>';
            mainRow += '<td class="text-center"><button class="btn btn-danger dim deleteRow" type="button"><i class="fa fa-trash"></i></button></td>';
            mainRow += '</tr>';

            $('#goodAppend').append(mainRow);
            $('#goodModal').modal('hide');
        });

        $(document).on("click", ".deleteRow", function () {
            const tr = $(this).closest('tr');
            tr.remove();
        });

        $(document).on("click", ".getRedLabelModal", function () {
            currentRow = $(this).closest('tr');
            $('#dataRedLabels').empty();
            $.ajax({
                url: '/red-label-table',
                type: 'get',
                success: function (response) {
                    $('#dataRedLabels').html(response.html);
                    $("#redLabelTable").dataTable();
                    $('#redLabelModal').modal('show');
                },
                error: function () {
                    swal("ติดต่อฐานข้อมูลไม่ได้ โปรดแจ้งเจ้าหน้าที่");
                }
            });
        });

        $(document).on("click", ".btnGetRedLabel", function () {
            const redLabelRow = $(this).closest("tr");
            const red_label_good_id = redLabelRow.find('.red_label_good_id').val();
            const red_label_good_code = redLabelRow.find('.red_label_good_code').val();
            const red_label_good_name = redLabelRow.find('.red_label_good_name').val();
            const red_label_unit_name = redLabelRow.find('.red_label_unit_name').val();

            const numberRow = currentRow.attr("data");

            let mainRow = '<tr>';
            mainRow += '<input type="hidden" name="productGoodId['+numberRow+'][]" class="goodId" value="'+red_label_good_id+'">';
            mainRow += '<td class="goodCode">'+red_label_good_code+'</td>';
            mainRow += '<td class="goodName">'+red_label_good_name+'</td>';
            mainRow += '<td class="goodProductAmount"><input type="number" name="productAmount['+numberRow+'][]" value="0" min="0.1" class="form-control"></td>';
            mainRow += '<td class="goodUnitName">'+red_label_unit_name+'</td>';
            mainRow += '<td class="text-center"><button class="btn btn-danger dim deleteRow" type="button"><i class="fa fa-trash"></i></button></td>';
            mainRow += '</tr>';

            currentRow.find('.redLabelAppend').append(mainRow);
            $('#redLabelModal').modal('hide');
        });

        $(document).on("click", "#submitButton", function () {
            $('#FormSubmit').submit();
        });
    </script>
@stop
