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
                <a href="/whs-center/members/set-members/showProfile/{{ $member->id }}"> ข้อมูลสมาชิก </a>
            </li>
            <li class="breadcrumb-item active">
                <strong>รายละเอียดคะแนน</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            <a href="{{ url()->previous() }}" class="btn btn-primary"> ย้อนกลับ </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="row">
            <div class="ibox ">
                    <div class="ibox-content profile-content">
                        <div class="panel-heading"><h2><strong>{{ $member->customer->name }}</strong></h2></div>
                        <div class="panel-body">
                            <h3><b>รหัสลูกค้า : </b>{{ $member->customer->code }}  </h3>
                            <h3><b>รหัสสมาชิก : </b>{{ $member->code }} </h3>
                            <h3><b>ประเภทสมาชิก :  </b> {{ $member->member_type->name }} </h3>
                        </div>

                        <table class="table table-bordered" style="width:100%">

                            <thead class="panel-heading">
                                <tr>
                                    <th style="text-align:center">รหัส HS</th>
                                    <th style="text-align:center" >วันที่</th>
                                    <th style="text-align:center">ราคารวม</th>
                                    <th style="text-align:center">ส่วนลด</th>
                                    <th style="text-align:center">ราคาหลังหักส่วนลดแล้ว</th>

                                    <th style="text-align:center">ราคาสุทธิ</th>
                                    <th style="text-align:center">แต้มที่ได้</th>
                                    <th ></th>
                                </tr>
                            </thead>

                            <tbody >
                                @foreach($resutls_array as $result)
                                <tr >
                                    <th style="text-align:center">{{ $result['hs']->code }}</th>
                                    <th style="text-align:center">{{  date("d/m/Y", strtotime($result['hs']->doc_date)) }}</th>
                                    <th style="text-align:center">{{ number_format($result['hs']->amount_total,2) }}</th>
                                    <th style="text-align:center">{{ number_format($result['hs']->discount_all,2)  }}</th>
                                    <th style="text-align:center">{{ number_format($result['hs']->after_discount,2)  }}</th>
                                    <th style="text-align:center">{{ number_format($result['hs']->total_amount,2)  }}</th>

                                        @if($result['totalPoint'] > 0)
                                            <th style="text-align:center; color:green;"> + {{$result['totalPoint'] }}</th>
                                        @else
                                            <th style="text-align:center;"> {{$result['totalPoint'] }}</th>
                                        @endif

                                    <th style="text-align:center"><a href="/whs-center/members/set-members/showProfile/{{ $member->id }}/hs-bill-point/{{ $result['hs']->id }}" class="btn btn-primary"><i class="fa fa-search-plus"></i> ดูรายละเอียด</a></th>

                                </tr>
                                @endforeach
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    {{-- <th></th>
                                    <th></th> --}}
                                    <th style="text-align:center">รวม</th>
                                    <th style="text-align:center; color:green;">{{$totalAmount}} </th>
                                    <th style="text-align:center; color:green;">{{$pointAllBill}}</th>
                                    <th></th>
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
