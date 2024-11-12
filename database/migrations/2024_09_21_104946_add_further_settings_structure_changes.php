<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backup existing data
        $existingSettings = DB::table('settings')->get();

        // Drop existing table
        Schema::dropIfExists('settings');

        // Create new structure
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type');
            $table->json('options')->nullable();
            $table->json('value');
            $table->boolean('enabled')->default(true);
            $table->string('affected_users')->default('all');
            $table->string('affected_areas')->default('site');
            $table->timestamps();
        });

        // Migrate existing data
        foreach ($existingSettings as $setting) {
            $newSetting = [
                'key' => $setting->title,
                'name' => $setting->label,
                'type' => $this->determineType($setting->option_type),
                'options' => $this->jsonEncodeIfNotNull($setting->option_values),
                'value' => $this->jsonEncodeIfNotNull($setting->value),
                'enabled' => $setting->enabled === 'true',
                'affected_users' => $setting->affected_users,
                'affected_areas' => $setting->affected_areas,
                'created_at' => $setting->created_at,
                'updated_at' => $setting->updated_at,
            ];

            DB::table('settings')->insert($newSetting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    private function determineType($optionType)
    {
        switch ($optionType) {
            case 'select':
                return 'select';
            case 'text':
                return 'text';
            case 'multiple-option':
                return 'multiple_select';
            case 'multiple-text':
                return 'key_value';
            case 'multiple-option-text':
                return 'nested_select';
            case 'multiple-option-bool':
                return 'nested_boolean';
            default:
                return 'json';
        }
    }

    private function jsonEncodeIfNotNull($value)
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            // If it's not already a valid JSON, encode it
            return json_encode($value);
        }

        // If it's already a valid JSON, return as is
        return $value;
    }
};
