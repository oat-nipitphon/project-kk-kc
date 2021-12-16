@extends('layouts-whs-center.app')

@section('content')
<div class="row">
    <div class="ibox">
        <div class="ibox-title">
            <span class="pull-right">
                <button type="button" id="insertMemberTypeBtn" class="btn btn-primary">
                    <i class="fa fa-plus"></i> เพิ่มประเภทกลุ่มสมาชิก
                </button>
            </span>
            <h3>จัดการประเภทกลุ่มสมาชิก</h3>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered" id="member_type_table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ประเภทกลุ่มสมาชิก</th>
                        <th>สร้างเมื่อ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')

<script>
   let member_type_table = '';
   $("#member_type_table").ready(function () {
       member_type_table = $('#member_type_table').DataTable({

           "searching": true,
           "responsive": true,
           "bFilter": false,
           "bLengthChange": true,
           "destroy": true,
           "pageLength": 10,
           "order": [
               [0, "asc"]
           ],
           "ajax": {
               "url": "/whs-center/members/set-member-types",
               "method": "POST",
               "data": {
                   "_token": "{{ csrf_token()}}",
               },
           },
           'columnDefs': [{
                   "targets": 0,
                   "className": "text-center",
               },
               {
                   "targets": 3,
                   "className": "text-center",
               },
           ],
           "columns": [{
                   "data": "id",
               },
               {
                   "data": "name",
               },
               {
                   "data": "created_at",
               },
               {
                   "data": "id",
                   "render": function (data, type, full) {
                       const general = `<button class="btn btn-warning btn-sm" onclick="editMemberType(${data},'${full.name}')" disabled><i class="fa fa-pencil"></i> แก้ไข</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMemberType(${data})" disabled><i class="fa fa-minus" ></i> ลบ</button>
                                `;
                       const etc = `<button class="btn btn-warning btn-sm" onclick="editMemberType(${data},'${full.name}')"><i class="fa fa-pencil"></i> แก้ไข</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMemberType(${data})"><i class="fa fa-minus"></i> ลบ</button>
                                `
                       return (data == 1) ? general : etc;
                   }
               },

           ],
       });

   });
   
   $("#insertMemberTypeBtn").click(function () {
       Swal.fire({
           title: "เพิ่มประเภทกลุ่มสมาชิก",
           text: "กรอกชื่อประเภทกลุ่มสมาชิก",
           input: 'text',
           showCancelButton: true,
           inputValidator: (value) => {
               if (!value) {
                   return 'กรุณากรอกข้อมูลด้วยครับ';
               }
           }
       }).then((result) => {
           if (result.value) {
               console.log("Result: " + result.value);
               $.post("/whs-center/members/set-member-types/storeMemberType", data = {
                       _token: '{{ csrf_token() }}',
                       name: result.value,
                   },
                   function (res) {
                       console.log('Store SUCCESS!!');
                       (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                       member_type_table.ajax.reload(null, false);
                   },
               );
           }
       });
   });

   function editMemberType(id, name) {
       Swal.fire({
           title: "แก้ไขชื่อประเภทกลุ่มสมาชิก ",
           input: 'text',
           inputValue: name,
           showCancelButton: true
       }).then((result) => {
           if (result.value) {
               console.log("Result: " + result.value);
               $.post("/whs-center/members/set-member-types/editMemberType", data = {
                       _token: '{{ csrf_token() }}',
                       id: id,
                       name: result.value,
                   },
                   function (res) {
                       console.log('Store SUCCESS!!');
                       (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                       member_type_table.ajax.reload(null, false);
                   },
               );
           }
       });
   }

   function deleteMemberType(id) {
       Swal.fire({
           title: 'คุณมั่นใจหรือไม่ ?',
           text: "คุณค้องการลบประเภทกลุ่มสมาชิกนี้หรือไม่",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'ตกลง',
           cancelButtonText: 'ยกเลิก',
       }).then((result) => {
           if (result.value) {
               $.post("/whs-center/members/set-member-types/deleteMemberType", data = {
                       _token: '{{ csrf_token() }}',
                       id: id,
                   },
                   function (res) {
                       (res.status == 'success') ? swal.fire(res.title, res.msg, res.status): false;
                       member_type_table.ajax.reload(null, false);
                   },
               );
           }
       });
   }
</script>
@stop
