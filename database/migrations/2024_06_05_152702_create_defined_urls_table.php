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
            $table->ulid()->primary();
            $table->uuid("session")->index("session");
            $table->string("original", 2083);
            $table->string("short", 2083);
            $table->text("hash");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defined_urls');
    }
};
