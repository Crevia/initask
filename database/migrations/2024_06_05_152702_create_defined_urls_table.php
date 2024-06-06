<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('defined_urls', function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->string("original", 2083);
            $table->string("short", 10)->index();
            $table->string("full_url", 2083)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('url_owners', function (Blueprint $table) {

            $table->foreignUlid("defined_url_id")->index();
            $table->ulid("session_id")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defined_urls');
        Schema::dropIfExists('url_owners');
    }
};
