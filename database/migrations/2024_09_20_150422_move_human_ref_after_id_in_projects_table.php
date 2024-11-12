<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MoveHumanRefAfterIdInProjectsTable extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // First, we need to drop any foreign key constraints on human_ref if they exist
            $foreignKeys = $this->listTableForeignKeys('projects');
            if (in_array('projects_human_ref_foreign', $foreignKeys)) {
                $table->dropForeign('projects_human_ref_foreign');
            }

            // Now we rename the existing column
            $table->renameColumn('human_ref', 'human_ref_old');
        });

        // Add the new column in the desired position
        Schema::table('projects', function (Blueprint $table) {
            $table->string('human_ref')->after('id');
        });

        // Copy data from old column to new column
        DB::table('projects')->update([
            'human_ref' => DB::raw('`human_ref_old`')
        ]);

        // Remove the old column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('human_ref_old');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('human_ref', 'human_ref_new');
            $table->string('human_ref')->after('updated_at');
        });

        DB::table('projects')->update([
            'human_ref' => DB::raw('`human_ref_new`')
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('human_ref_new');
        });
    }

    private function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }
}