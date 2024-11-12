<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id()->from(1000);
            $table->string('title');
            $table->longText('content');
            $table->longText('short_description')->nullable();
            $table->string('author')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('are_tags_generated')->default(false);
            $table->string('slug')->default(Str::random(10))->nullable();
            $table->integer('view_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
};
