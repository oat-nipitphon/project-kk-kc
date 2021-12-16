@extends('layouts-whs-center.app')

@section('css')
    <link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@stop

@section('content')
    <form action="{{ route('whs-center.goods.set-check-goods.setMinMax', $good->id) }}" method="POST" class="form-horizontal" id="setMinMaxForm">
        @csrf
        <div class="ibox">
            <div class="ibox-title">
                <h2>ตั้งค่าตรวจสอบสินค้า</h2>
            </div>
            <div class="ibox-content ibox-padding">
                <div class="form-group">
                    <label class="col-sm-3 control-label">รหัสสินค้า :</label>
                    <label class="col-sm-9" style="padding-top:7px;">{{ $good->code }}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">ชื่อสินค้า :</label>
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
                                <th>ต่ำสุด</th>
                                <th>สูงสุด</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>
                                        <input type="number" name="min[{{ $warehouse->id }}]" value="{{ $good->amountMin($warehouse->id) }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="max[{{ $warehouse->id }}]" value="{{ $good->amountMax($warehouse->id) }}" class="form-control">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="ibox-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="formSubmitButton">
                        บันทึก
                    </button>
                </div>
            </div>
        </div>
    </form>
@stop

@section('script')
    <script>
        $(document).on("click", "#formSubmitButton", function () {
            $(this).attr('disabled', true);
            $(this).val("รอสักครู่...")
            $("#setMinMaxForm").submit();
        });
    </script>
@stop
