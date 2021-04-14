<?php

namespace Modules\Item\Dao\Repositories;

use Plugin\Helper;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Modules\Item\Dao\Repositories\LinenRepository;
use Modules\Sales\Dao\Repositories\OrderRepository;

class ReportLinenRegisterRepository extends LinenRepository implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    public function headings(): array
    {
        return [
            'Code RFID',
            'Rumah Sakit',
            'Ruangan',
            'Nama Linen',
            'Register Oleh',
            'Tanggal Register',
            'Tanggal Update',
            'Status',
        ];
    }

    public function __construct()
    {
        \Carbon\Carbon::setLocale(config('app.locale'));
    }

    public function collection()
    {
        $query = $this->dataRepository()->addSelect([$this->getCreatedAtColumn(), $this->getUpdatedAtColumn()]);
        
        if ($item_linen_company_id = request()->get('item_linen_company_id')) {
            $query->where('item_linen_company_id', $item_linen_company_id);
        }
        if ($item_linen_location_id = request()->get('item_linen_location_id')) {
            $query->where('item_linen_location_id', $item_linen_location_id);
        }
        if ($item_linen_product_id = request()->get('item_linen_product_id')) {
            $query->where('item_linen_product_id', $item_linen_product_id);
        }
        if ($item_linen_rent = request()->get('item_linen_rent')) {
            $query->where('item_linen_rent', $item_linen_rent);
        }
        if ($from = request()->get('from')) {
            $query->whereDate('item_linen_created_at', '>=', $from);
        }
        if ($to = request()->get('to')) {
            $query->whereDate('item_linen_created_at','<=', $to);
        }
        return $query->whereNull('item_linen_deleted_at')->get();
    }

    public function map($data): array
    {
        return [
           $data->item_linen_rfid, 
           $data->company_name, 
           $data->location_name, 
           $data->item_product_name, 
           $data->name, 
           $data->item_linen_created_at ? $data->item_linen_created_at->isoFormat('dddd, D MMMM Y') : '', 
           $data->item_linen_updated_at ? $data->item_linen_updated_at->isoFormat('dddd, D MMMM Y') : '', 
           $data->rent[$data->item_linen_rent][0] ?? '', 
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}