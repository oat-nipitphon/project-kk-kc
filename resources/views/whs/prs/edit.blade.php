@extends('layouts-whs.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('whs.prs.update', $pr->id) }}" class="form-horizontal" id="prFormSubmit">
            @csrf
            @method("PUT")
            <input type="hidden" name="updated_at" value="{{ $pr->updated_at }}">
            <div class="ibox">
                <div class="ibox-title">
                    <h3>แก้ไขใบขอซื้อ (Purchase Requisition)</h3>
                    <button type="button" class="btn btn-primary" onclick="prFormSubmit()" id="submitButton">บันทึก</button>
                    <a href="{{ route('whs.prs.show', $pr->id) }}" class="btn btn-warning waves-effect">ยกเลิก</a>
                </div>
                @include('whs.prs.form')
            </div>
        </form>
    </div>

    <table style="display: none;">
        <tbody>
        <tr class="addTr">
            <td>
                <span class="good_code"></span>
                <input type="hidden" name="good_id[]" value="" class="good_id">
            </td>
            <td>
                <span class="good_name"></span><br><br>
                <textarea name="goodDetail[]" cols="50" rows="10" class="form-control"></textarea>
            </td>
            <td>
                <label>
                    <input type="text" name="amount[]" class="form-control amount" data-type="number">
                </label>
            </td>
            <td>
                <span class="good_unit"></span>
            </td>
            <td>
                <span class="amount_in_warehouse"></span>
            </td>
            <td class="text-center">
                <button type="button" name="button" class="btn btn-danger deleteRow">ลบ</button>
            </td>
        </tr>
        </tbody>
    </table>
@endsection

@section('script')
    <script src="/js/plugins/dataTables/datatables.min.js"></script>
    <script type="text/javascript">
        $("#tableGood").dataTable();

        function prFormSubmit() {
            var warehouse_id = $('#warehouse').val();
            var required_at = $('#required_at').val();
            if (warehouse_id == 0) {
                swal("กรุณาเลือกคลังสินค้า");
            } else if (required_at == '') {
                swal("กรุณากรอกวันที่ต้องการสินค้า");
            } else {
                $('#submitButton').attr("disabled", true);
                $('#prFormSubmit').submit();
            }
        }

        function getGoodModal() {
            $('#goodModal').modal('show');
        }

        $('#selectProduct').on('click', function () {
            tr = $('#tableGood').find('tbody').find('tr');
            tr.each(function () {
                if ($(this).find('.product-class').prop('checked')) {
                    var addRow = $(' .addTr ').clone(true);
                    addRow.removeClass(' addTr ');
                    addRow.find(' .good_code ').text($(this).find('.goodCode').text());
                    addRow.find(' .good_id ').val($(this).find('.goodId').val());
                    addRow.find(' .good_name ').text($(this).find('.goodName').text());
                    if ($(this).find('.typeId').val() == 1) {
                        addRow.find(' .good_unit ').text("กิโลกรัม");
                    } else {
                        addRow.find(' .good_unit ').text($(this).find('.goodUnitName').text());
                    }
                    addRow.find(' .amount_in_warehouse ').text($(this).find('.goodAmount').text());
                    $('#goodAppend').append(addRow);
                }
            });
            $('#goodModal').modal('hide');
            $('input[type=checkbox]').prop('checked', false);
        });

        $('.deleteRow').on('click', function () {
            $(this).closest('tr').remove();
        });
    </script>
@stop
