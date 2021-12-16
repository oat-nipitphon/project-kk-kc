

            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">w_g_id</th>
                        <th class="text-center">good_id</th>
                        <th class="text-center">รหัสสินค้า</th>
                        <th class="text-center" style="width: 30%">ชื่อสินค้า</th>
                        <th class="text-center">หน่วยนับ</th>
                    </tr>
                </thead>

                    {{-- @foreach ($data as $item) --}}
                    <tr>
                    {{-- <th class="text-center">{{ $item->warehouse_id }}</th> --}}
                    <th class="text-center">good_id</th>
                    <th class="text-center">รหัสสินค้า</th>
                    <th class="text-center" style="width: 30%">ชื่อสินค้า</th>
                    <th class="text-center">หน่วยนับ</th>
                    </tr>
                    {{-- @endforeach --}}

            </table>


