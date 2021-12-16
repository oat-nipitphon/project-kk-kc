<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>exeport-excel</title>
    <style>
        table {
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          padding: 8px;
        }

        tr:nth-child(even) {

        }
        </style>
</head>
<body>
    <div class="row">
    <div class="ibox">
        <div class="ibox-title">
            <h3>Export Excel Example</h3><br><br>
        <h4>รายการสั่งซื้อและแต้มสะสม ของ {{ $thead['memberType'] }} สาขา {{ $thead['warehouse'] }}<h4>
        </div>
        <div class="ibox-content">
            <table style="width : 100%">
                <thead>
                    <tr style="text-align:center;">
                        <th rowspan="2">ลำดับ</th>
                        <th rowspan="2">รหัสสมาชิก</th>
                        <th rowspan="2">ประเภทสมาชิก</th>
                        <th rowspan="2">รายชื่อ</th>
                        <th rowspan="2">เลขบัตรประจำตัวประชาชน</th>
                        <th rowspan="2">ธนาคาร</th>
                        <th rowspan="2">เลขบัญชี</th>
                        <th colspan="3">เดือน {{$thead['month']}}</td>

                        <th colspan="3">รวมทั้งสิ้นตั้งแต่เริ่มโครงการ</td>

                    </tr>
                    <tr style="text-align:center;">
                        <th>ผลประโยชน์</th>
                        <th>ยอดบิล</th>
                        <th>คะแนน</th>
                        <th>ผลประโยชน์</th>
                        <th>ยอดบิล</th>
                        <th>คะแนน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $tbodies as $tbody)
                        <tr>
                            <td>{{ $tbody['no'] }}</td>
                            <td>{{ $tbody['member_code'] }}</td>
                            <td>{{ $tbody['member_type'] }}</td>
                            <td>{{ $tbody['name'] }}</td>
                            <td>{{ $tbody['vat_code'] }}</td>
                            <td>{{ $tbody['bank'] }}</td>
                            <td>{{ $tbody['bank_account_number'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;">{{ $tbody['total_balance'] }}</td>
                            <td style="text-align:right;">{{ $tbody['total_point'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
