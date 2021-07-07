<table id="transaction" class="table table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">No. Seri RFID</th>
            <th class="text-left col-md-4">Product Name</th>
            <th class="text-left col-md-3">Default R.S</th>
            <th class="text-left col-md-2">Default Location</th>
            <th class="text-center col-md-1">Action</th>
        </tr>
    </thead>
    <tbody class="markup">
        @if(!empty($detail) || old('detail'))
        @foreach (old('detail') ?? $detail as $item)
        <tr>
            <td data-title="Product">
                <input type="hidden" value="{{ $item['temp_id'] ?? $item->linen_grouping_detail_rfid ?? '' }}"
                    name="detail[{{ $loop->index }}][temp_id]">

                <input type="text" readonly class="form-control input-sm"
                    value="{{ $item['temp_product'] ?? $item->linen_grouping_detail_rfid ?? old('temp_id') }}"
                    name="detail[{{ $loop->index }}][temp_product]">

            </td>
            <td data-title="Description">
            <input type="text" readonly class="form-control input-sm"
                    value="{{ $item['temp_product'] ?? $item->linen_grouping_detail_product_name ?? old('temp_id') }}"
                    name="detail[{{ $loop->index }}][temp_product]">
            </td>
            <td data-title="Qty" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}1" name="detail[{{ $loop->index }}][temp_qty]"
                    class="form-control input-sm" readonly
                    value="{{ $item['temp_qty'] ?? $item->linen_grouping_detail_ori_company_name }}">
            </td>
            <td data-title="Send" class="text-right col-lg-1">
                <input type="text" tabindex="{{ $loop->iteration }}2" name="detail[{{ $loop->index }}][temp_out]"
                    class="form-control input-sm" readonly
                    value="{{ $item['temp_qty'] ?? $item->linen_grouping_detail_ori_location_name }}">

            </td>
            <td data-title="Send" class="text-right col-lg-1">
                
                <a class="btn btn-danger btn-sm btn-block" href="http://">Delete</a>

            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>

</table>