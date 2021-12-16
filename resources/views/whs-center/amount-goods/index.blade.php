@extends('layouts-whs-center.app')
<style>
    html {
    scroll-behavior: smooth;
    }

    #section1 {
    height: 10px;
    background-color: white;
    }

</style>
@section('content')

<div id="loader"></div>
{{-- Menu Top --}}
<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2>ยอดคงเหลือ</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="http://127.0.0.1:8000/whs-center/dashboard">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <strong>ยอดคงเหลือ</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="row wrapper white-bg">
        <div class="col-lg-5"></div>

        <div class="col-lg-4">
            <select class="form-control selectType">
                <option value="กรุณาเลือกประเภท">กรุณาเลือกประเภท</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option >
                    @endforeach
            </select>
        </div>

        <div class="col-lg-3" id="setviewmain">
            <a href="#section2">
                <button type="submit" class="btn btn-info clickAmountGoodWarehouse">
                    <i class="fa fa-search-plus"></i>
                    คำนวณจำนวนสินค้า
                </button>
            </a>
        </div>

    </div>
</div>


{{-- List Date Good View --}}
    <div class="formDataTable viewtb"></div>


@endsection
@section('script')

<script>

    $(document).on("change paste keyup", ".selectType", function() {

        typeId = $(this).val();
        if (typeId != 'กรุณาเลือกประเภท') {
            ajaxSearchGoodFormType(typeId);
        }

    });

    function ajaxSearchGoodFormType(typeId) {

        $.ajax({
            type: "POST",
            url: "/whs-center/repost/amount-goods/search-good-form-type",
            data: {
                "_token": "{{ csrf_token() }}",
                "type_id": typeId
            },
            success: function (data) {
                $('.formDataTable').html(data.html);
            }
        });
    }

    $(document).on("click", ".clickAmountGoodWarehouse", function() {

        $(this).attr('disabled', true);
        calAmountGoodWarehouse();
        $(this).attr('disabled', false);

    });

    function calAmountGoodWarehouse() {
        $('.trGood').each(function() {
            row = $(this);
            goodId = $(this).find('.goodId').val();
            ajaxCheckAmount(row, goodId);
        });
    }

    function ajaxCheckAmount(row, goodId) {

        $.ajax({
            type: "POST",
            url: "/whs-center/repost/amount-goods/check-amount",
            data: {
                "_token": "{{ csrf_token() }}",
                "good_id": goodId
            },
            async: false,
        }).done(function (data) {
            $.each(data , function(i, value) {
                if (value['goodview'] != null) {
                    console.log(+value['goodview']['balance_amount']);
                    row.find('.'+value['code']).text(+value['goodview']['balance_amount']);
                }else {
                    row.find('.'+value['code']).text(0);
                }
            })
        });

    }

</script>

@endsection
