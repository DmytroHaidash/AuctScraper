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
        Schema::create('scrapers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->boolean('run')->default(0);
            $table->integer('period')->default(2);
            $table->unsignedBigInteger('type_auction_id')->nullable();
            $table->dateTime('last_scraped_at')->nullable();
            $table->timestamps();

            $table->foreign('type_auction_id')->references('id')->on('type_auctions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrapers');
    }
};
