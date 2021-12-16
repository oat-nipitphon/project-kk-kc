<style>
    .dtHorizontalExampleWrapper {
    max-width: 600px;
    margin: 0 auto;
    }
    #dtHorizontalExample th, td {
    white-space: nowrap;
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:before {
    bottom: .5em;
    }

    div.div1 {
    margin-top: 50px;
    }



</style>

    <div class="div1">
        <table id="dtHorizontalExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ชื่อสินค้า</th>
                    @foreach ($warehouses as $warehouse)
                        <th>{{ $warehouse->code }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($goods as $good)
                    <tr class="trGood">
                        <td>
                            {{ $good->name }}
                            <input type="hidden" class="goodId" value="{{ $good->id }}">
                        </td>
                        @foreach ($warehouses as $warehouse)
                            <td>
                                <label class="{{ $warehouse->code }}">0</label>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<script>

    $(document).ready(function () {

    $('#dtHorizontalExample').DataTable({
    "scrollX": true
    });
    $('.dataTables_length').addClass('bs-select');

    });

</script>
