<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Design;
use Illuminate\Support\Facades\Log;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->string('slug', 100)->after('title')->nullable()->unique();
        });

        // Seed existing designs with slugs
        Design::chunk(100, function ($designs) {
            foreach ($designs as $design) {
                try {
                    $design->slug = $this->generateSlug($design);
                    $design->save();
                } catch (\Exception $e) {
                    \Log::error('Error generating slug for design: ' . $design->id . ' - ' . $e->getMessage());
                }
            }
        });
    }

    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function generateSlug($design)
    {
        $prefix = $this->getSlugPrefix($design->title);
        $size = $design->size;
        $length = number_format($design->length, 1, '.', '');
        $width = number_format($design->width, 1, '.', '');

        return "{$prefix}-{$size}m2-{$length}m-na-{$width}m";
    }

    private function getSlugPrefix($title)
    {
        $prefixMap = [
            'Д-ОЦБ' => 'dom-iz-brevna',
            'Д-ПБ' => 'dom-iz-brusa',
            'Б-ОЦБ' => 'banya-iz-brevna',
            'Б-ПБ' => 'banya-iz-brusa',
        ];

        foreach ($prefixMap as $key => $value) {
            if (str_starts_with($title, $key)) {
                return $value;
            }
        }

        return 'design'; // Default prefix if no match found
    }


};
