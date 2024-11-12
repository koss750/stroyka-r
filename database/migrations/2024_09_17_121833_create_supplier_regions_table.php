<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Supplier;
use App\Models\Region;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('supplier_regions');
        Schema::create('supplier_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        $suppliers = Supplier::all();
        foreach ($suppliers as $supplier) {
            if ($supplier->region_code) {
                $region = Region::firstOrCreate(['id' => $supplier->region_code]);
                $supplier->regions()->attach($region->id);
            }
        }

        // Step 2: Remove the region_code column from suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('region_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add the region_code column back to suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('region_code')->nullable();
        });

        // Step 2: Transfer data back from supplier_regions to suppliers.region_code
        $suppliers = Supplier::with('regions')->get();
        foreach ($suppliers as $supplier) {
            $regionCodes = $supplier->regions->pluck('id')->implode(', ');
            $supplier->region_code = $regionCodes;
            $supplier->save();
        }
        
        Schema::dropIfExists('supplier_regions');
    }
};
