@extends('layouts-whs-center.app')

@section('css')

@stop

@section('content')


<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>รายละเอียดคะแนน</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('whs-center/dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('whs-center/members/set-members') }}"> ตั้งค่าสมาชิก </a>
            </li>
            <li class="breadcrumb-item active">
            <a href="/whs-center/members/set-members/showProfile/{{$member_id}}"> ข้อมูลสมาชิก </a>
            </li>
            <li class="breadcrumb-item active">
            <a href="/whs-center/members/set-members/showProfile/{{$member_id}}/showPointDetail">รายละเอียดคะแนน</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>HS Bill</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            {{-- <a href="{{ $header['hs_id']}}/print-hs-bill" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Print HSBill </a> --}}
            <a href="{{ url()->previous() }}" class="btn btn-primary"> ย้อนกลับ </a>
        </div>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
    <div class="col-lg-12">
            <div class="ibox-content p-xl">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>From:</h5>
                            <address>
                                <strong>สาขา {{ $header['warehouse'] }}</strong><br>
                                <br>
                                <br>
                            </address>
                        </div>

                        <div class="col-sm-6 text-right">
                            <h4>HS Bill</h4>
                            <h4 class="text-navy">{{ $header['hs_code']}}</h4>
                            <span><strong>วันที่ </strong>  {{ $header['hs_date'] }} </span><br><br>
                            <address>
                                <strong>{{ $customer->name}}</strong><br>
                                {{ $customer->customerBillAddress->address}}<br>
                                {{ $customer->customerBillAddress->district}}, {{ $customer->customerBillAddress->amphoe}}<br>
                                {{ $customer->customerBillAddress->province}}, {{ $customer->customerBillAddress->zipcode}}<br>
                                โทรศัพท์ : {{ $customer->tel}}
                            </address>
                            <span><strong>เลขประจำตัวผู้เสียภาษี  </strong> {{ $customer->vat_code }}</span><br><br>
                           <br>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table style="width:100%">
                            <thead class="panel-heading">
                            <tr>
                                <th>ลำดับ</th>
                                <th>รายการ</th>
                                <th>จำนวน</th>
                                <th>หน่วย</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>ราคาก่อน Vat</th>
                                <th>ราคาหลัง Vat</th>
                                <th>บาท : แต้ม </th>
                                <th>แต้มที่ได้</th>

                            </tr>
                            </thead>
                            <tbody>
                                @foreach($bodies as $body)
                                <tr style="text-align:center;">
                                    <td style="text-align:left;">{{ $body['no'] }} </td>
                                    <td style="width:400px; text-align:left;" ><div> {{ $body['good_name'] }} </div><small> รหัสสินค้า : {{ $body['good_code'] }}</small></td>
                                    <td>{{ $body['amount'] }}</td>
                                    <td>{{ $body['good_unit'] }}</td>
                                    <td>{{ $body['price_unit'] }}</td>
                                    <td>{{ $body['total_price_vat'] }}</td>
                                    <td>{{ $body['total_price'] }}</td>
                                    <td>{{ $body['ratio'] }}</td>
                                    <td>{{ $body['point'] }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /table-responsive -->

                    <table class="table invoice-total">
                        <tbody>
                        <tr>
                            <td><strong>ราคารวมก่อนVAT </strong></td>
                            <td>{{ $footer['totalPriceVatHS'] }} บาท</td>
                        </tr>
                        <tr>
                            <td><strong>ราคารวมหลังVAT</strong></td>
                            <td>{{ $footer['totalPriceHs'] }} บาท</td>
                        </tr>
                        <tr>
                            <td><strong>ส่วนลด</strong></td>
                            <td>{{ $footer['discount'] }} บาท</td>
                        </tr>
                        <tr>
                            <td><strong>ราคาสุทธิ</strong></td>
                            <td>{{ $footer['netPrice'] }} บาท</td>
                        </tr>
                        <tr>
                            <td><strong>แต้มรวม</strong></td>
                            <td>{{ $footer['sumPoint'] }} แต้ม</td>
                        </tr>
                        <tr>
                            <td><strong>แต้มที่ได้</strong></td>
                            <td>{{ $footer['pointInBill'] }} แต้ม</td>
                        </tr>
                        <tr>
                            <td><strong>แต้มสะสมทั้งหมด</strong></td>
                            <td>{{ $footer['pointAllBill'] }} แต้ม</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
        </div>
    </div>
</div>


@endsection

@section('script')

<script>


</script>

@stop
