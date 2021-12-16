@extends('layouts-inv.app')

@section('content')

{{-- ฺMenu Top --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>รายการใบเบิกสินค้าที่สร้าง</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong><a href="{{ route('select-program') }}">หน้าหลัก</strong>
                /
                <strong><a href="{{ route('kc-inv.requisitions') }}">รายการใบเบิกสินค้าที่สร้าง</a></strong>
            </li>
        </ol>
    </div>
</div>

{{-- Menu Create --}}
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="ibox-title col-lg-9">
            <a href="{{ route('kc-inv.requisitions.view-create') }}">
            <button type="button" id="btn-config" class="btn btn-primary">สร้างใบเบิก</button>
            </a>
        </div>
        <div class="ibox-title col-lg-3">
            <span class="pull-right">เลขที่เอกสาร xxx-xxx-xxx</span>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="row card-body white-bg page-heading">
    <div id="wrapper">
        <div class="col-lg-12">
            <div class="table-responsive" style="margin-top:25px;">
                <table class="table table-sm table-striped" id="search_table" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center">คลังสินค้าที่เบิก</th>
                            <th class="text-center">เลขที่เอกสาร</th>
                            <th class="text-center">วันที่เอกสาร</th>
                            <th class="text-center">วัตถุประสงค์การเบิก</th>
                            <th class="text-center">ผู้บันทึก</th>
                            <th class="text-center">ผุ้อนุมัติ</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>

    //Data Table List Report Kc Hrm
    var search_table = $('#search_table').DataTable();

    // tables = (data) => {
    // search_table = $('#search_table').DataTable({
    // "ordering": true,
    // "bPaginate": true,
    // "searching": true,
    // "info": false,
    // "responsive": true,
    // "bFilter": false,
    // "bLengthChange": true,
    // "destroy": true,
    // "pageLength": 25,
    // "ajax": {
    //     "url": "/kc-hrm/hrm-report/search",
    //     "method": "POST",
    //     "data": {
    //         "_token": "{{ csrf_token()}}"
    //     },
    // },
    // "columns": [
    //     {
    //         "data": "id",
    //     },
    //     {
    //         "data": "request_type",
    //     },
    //     {
    //         "data": "start_time",
    //     },
    //     {
    //         "data": "end_time",
    //     },
    //     {
    //         "data": "status",
    //         "render":function(data){
    //             var html;
    //             if(data == 1){
    //                 html = '<span class="badge badge-info btn-block">รออนุมัติ</span>';
    //             }else if(data == 2){
    //                 html = '<span class="badge badge-primary btn-block">อนุมัติเรียบร้อย</span>';
    //             }else if(data == 3){
    //                 html = '<span class="badge badge-warning btn-block">ไม่ผ่านอนุมัติ</span>';
    //             }else{
    //                 html = 'อื่นๆ';
    //             }
    //             return html;
    //         }
    //     },
    //     {
    //         "data": "approves",
    //         "render":function(data,type,full){
    //             return (data)? full.approves.name : null;
    //         }
    //     },
    // ],
    // });
    // }
</script>
@endsection

