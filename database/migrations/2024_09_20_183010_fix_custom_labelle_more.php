<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\InvoiceType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //$this->fillIn();
    }

    public function down()
    {
        
    }

    public function fillIn() {
        $invoiceTypes = InvoiceType::where('site_level4_label', '!=', 'FALSE')->get();
        $exceptions = [307, 308, 309, 310, 192, 193, 194, 195];
        foreach ($invoiceTypes as $invoiceType) {
            $invoiceTypeId = $invoiceType->id;
            switch ($invoiceType->site_level4_label) {
                case 'TRUE':
                    $invoiceTypeDescription = $invoiceType->site_label . " " . $invoiceType->site_sub_label;
                    break;
                default:
                    $invoiceTypeDescription = $invoiceType->site_label . " " . $invoiceType->site_sub_label . " " . $invoiceType->site_level4_label;
                    break;
            }
            switch ($invoiceType->getOldestParentLabelAttribute()) {
                case 'f':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'"';
                    break;
                case 'd':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству домокомплекта " . '"'.$invoiceTypeDescription.'"';
                    break;
                case 'r':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству кровли " . '"'.$invoiceTypeDescription.'"';
                    break;
            }
            if (in_array($invoiceTypeId, $exceptions)) {
                switch ($invoiceTypeId) {
                case 307:
                    $invoiceType->custom_order_title .= ' Отмосток 900мм';
                    break;
                case 308:
                    $invoiceType->custom_order_title .= ' Отмосток 900мм';
                    break;
                case 309:
                    $invoiceType->custom_order_title .= ' Отмосток 900мм';
                    break;
                case 310:
                    $invoiceType->custom_order_title .= ' Отмосток 900мм';
                    break;
                case 192:
                    $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 30. 15-3" (150x3000)';
                    break;
                case 193:
                    $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 40. 15-3" (150x4000)';
                    break;
                case 194:
                    $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 30. 20-3" (200x3000)';
                    break;
                case 195:
                    $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 40. 20-3" (200x4000)';
                    break;
                }
            }
            $invoiceType->save();
        }
    }
};
