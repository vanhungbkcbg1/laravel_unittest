<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SymbolPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("symbol_prices",function (Blueprint $table){
           $table->float("low")->default(0);
           $table->float("high")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("symbol_prices",function (Blueprint $table) {
           $table->removeColumn("low");
           $table->removeColumn("high");
        });
    }
}
