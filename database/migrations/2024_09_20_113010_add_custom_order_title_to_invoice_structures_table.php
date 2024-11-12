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
        Schema::table('invoice_structures', function (Blueprint $table) {
            //$table->dropColumn('custom_order_title');
            $table->string('custom_order_title')->after('params')->nullable();
        });
        $this->fillIn();
    }

    public function down()
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->dropColumn('custom_order_title');
        });
    }

    public function fillIn() {
        $invoiceTypes = InvoiceType::where('site_level4_label', '!=', 'FALSE')->get();
        $exceptions = [307, 308, 309, 310, 192, 193, 194, 195];
        foreach ($invoiceTypes as $invoiceType) {
            $invoiceTypeId = $invoiceType->id;
            $parentLabel = $invoiceType->oldestParentLabel;
            switch ($invoiceType->site_level4_label) {
                case 'TRUE':
                    $invoiceTypeDescription = $invoiceType->site_label . " " . $invoiceType->site_sub_label;
                    break;
                default:
                    $invoiceTypeDescription = $invoiceType->site_label . " " . $invoiceType->site_sub_label . " " . $invoiceType->site_level4_label;
                    break;
            }
            switch ($parentLabel) {
                case 'f':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'"';
                case 'd':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству домокомплекта " . '"'.$invoiceTypeDescription.'"';
                case 'r':
                    $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству кровли " . '"'.$invoiceTypeDescription.'"';
            }
            switch ($invoiceTypeId) {
            case 307:
                $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'". Отмосток 900мм';
            case 308:
                $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'". Отмосток 900мм';
            case 309:
                $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'". Отмосток 900мм';
            case 310:
                $invoiceType->custom_order_title = "Раздел № {order} - строительно-монтажные работы по устройству фундамента " . '"'.$invoiceTypeDescription.'". Отмосток 900мм';
            case 192:
                $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 30. 15-3" (150x3000)';
            case 193:
                $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 40. 15-3" (150x4000)';
            case 194:
                $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 30. 20-3" (200x3000)';
            case 195:
                $invoiceType->custom_order_title = 'Раздел № {order} - строительно-монтажные работы по устройству свайного ЖБ фундамента "С 40. 20-3" (200x4000)';
            }
            
            
            $invoiceType->save();
        }
    }
};
